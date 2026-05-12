<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\VenueController;
use App\Http\Controllers\Admin\RegistrationController as AdminRegistrationController;
use App\Http\Controllers\Admin\AttendanceController as AdminAttendanceController;
use App\Http\Controllers\Admin\CertificateController as AdminCertificateController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Organizer\DashboardController as OrganizerDashboard;
use App\Http\Controllers\Organizer\EventController as OrganizerEventController;
use App\Http\Controllers\Organizer\AttendanceController as OrganizerAttendanceController;
use App\Http\Controllers\Organizer\ReportController as OrganizerReportController;
use App\Http\Controllers\Attendee\DashboardController as AttendeeDashboard;
use App\Http\Controllers\Attendee\EventController as AttendeeEventController;
use App\Http\Controllers\Attendee\RegistrationController as AttendeeRegistrationController;
use App\Http\Controllers\Attendee\CertificateController as AttendeeCertificateController;
use App\Http\Controllers\QrScanController;

// ── Public Landing Page ──────────────────────────────────────
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route(auth()->user()->role . '.dashboard');
    }
    return view('welcome');
})->name('home');

// ── Auth Routes ──────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',           [LoginController::class, 'showForm'])->name('login');
    Route::post('/login',          [LoginController::class, 'login']);
    Route::get('/register',        [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register',       [RegisterController::class, 'register']);
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])->name('password.request');
    Route::post('/forgot-password',[ForgotPasswordController::class, 'sendLink'])->name('password.email');
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')->name('logout');

// ── Admin Routes ─────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')->name('admin.')
    ->group(function () {

    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    // Users CRUD + Organizer approval + CSV import
    Route::resource('users', UserController::class);
    Route::patch('users/{user}/approve', [UserController::class, 'approveOrganizer'])->name('users.approve');
    Route::get('users/import/upload',    [UserController::class, 'showUpload'])->name('users.upload');
    Route::post('users/import/process',  [UserController::class, 'processUpload'])->name('users.import');

    // Events
    Route::resource('events', AdminEventController::class);
    Route::patch('events/{event}/status', [AdminEventController::class, 'updateStatus'])->name('events.status');

    // Categories & Venues
    Route::resource('categories', CategoryController::class);
    Route::resource('venues', VenueController::class);

    // Registrations
    Route::get('registrations', [AdminRegistrationController::class, 'index'])->name('registrations.index');
    Route::patch('registrations/{registration}/approve', [AdminRegistrationController::class, 'approve'])->name('registrations.approve');
    Route::patch('registrations/{registration}/reject',  [AdminRegistrationController::class, 'reject'])->name('registrations.reject');

    // Attendance
    Route::get('attendance',         [AdminAttendanceController::class, 'index'])->name('attendance.index');
    Route::get('attendance/scanner', [AdminAttendanceController::class, 'scanner'])->name('attendance.scanner');
    Route::post('attendance/scan',   [AdminAttendanceController::class, 'scan'])->name('attendance.scan');
    Route::post('attendance/manual', [AdminAttendanceController::class, 'manual'])->name('attendance.manual');

    // Certificates
    Route::get('certificates',                   [AdminCertificateController::class, 'index'])->name('certificates.index');
    Route::post('certificates/generate/{event}', [AdminCertificateController::class, 'generate'])->name('certificates.generate');
    Route::get('certificates/download/{cert}',   [AdminCertificateController::class, 'download'])->name('certificates.download');

    // Reports
    Route::get('reports',        [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/export', [ReportController::class, 'export'])->name('reports.export');
});

// ── Organizer Routes ─────────────────────────────────────────
Route::middleware(['auth', 'role:organizer'])
    ->prefix('organizer')->name('organizer.')
    ->group(function () {

    Route::get('/dashboard', [OrganizerDashboard::class, 'index'])->name('dashboard');

    Route::resource('events', OrganizerEventController::class);
    Route::patch('events/{event}/publish', [OrganizerEventController::class, 'publish'])->name('events.publish');

    Route::get('attendance/scanner',  [OrganizerAttendanceController::class, 'scanner'])->name('attendance.scanner');
    Route::post('attendance/scan',    [OrganizerAttendanceController::class, 'scan'])->name('attendance.scan');
    Route::get('attendance/records',  [OrganizerAttendanceController::class, 'records'])->name('attendance.records');

    Route::get('reports', [OrganizerReportController::class, 'index'])->name('reports.index');
});

// ── Attendee Routes ──────────────────────────────────────────
Route::middleware(['auth', 'role:attendee'])
    ->prefix('attendee')->name('attendee.')
    ->group(function () {

    Route::get('/dashboard', [AttendeeDashboard::class, 'index'])->name('dashboard');

    Route::get('events',                    [AttendeeEventController::class, 'browse'])->name('events.browse');
    Route::get('events/{event}',            [AttendeeEventController::class, 'show'])->name('events.show');
    Route::post('events/{event}/register',  [AttendeeRegistrationController::class, 'store'])->name('registrations.store');
    Route::get('my-registrations',          [AttendeeRegistrationController::class, 'index'])->name('registrations.index');
    Route::delete('registrations/{registration}/cancel', [AttendeeRegistrationController::class, 'cancel'])->name('registrations.cancel');
    Route::get('qr-code/{registration}',    [AttendeeRegistrationController::class, 'qrCode'])->name('qr.show');

    Route::get('certificates',                     [AttendeeCertificateController::class, 'index'])->name('certificates.index');
    Route::get('certificates/{cert}/download',     [AttendeeCertificateController::class, 'download'])->name('certificates.download');
});

// ── QR Scan API ───────────────────────────────────────────────
Route::middleware(['auth'])->prefix('api')->group(function () {
    Route::post('qr/scan', [QrScanController::class, 'scan'])->name('api.qr.scan');
});
