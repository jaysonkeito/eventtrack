# EventTrack — Laravel Setup Guide
# ITS 306 Web Development 2 | Jayson Francisco

## Requirements
- PHP >= 8.2
- Composer
- MySQL (via MySQL Workbench)
- VS Code with PHP Server extension

---

## Step-by-Step Setup

### 1. Create Laravel Project
```bash
composer create-project laravel/laravel eventtrack
cd eventtrack
```

### 2. Install Required Packages
```bash
# QR Code generation
composer require simplesoftwareio/simple-qrcode

# PDF certificate generation
composer require barryvdh/laravel-dompdf
```

### 3. Copy Project Files
Copy all files from this zip into your Laravel project root,
replacing existing files where prompted.

### 4. Configure Environment
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`:
```
DB_DATABASE=eventtrack_db
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 5. Register RoleMiddleware
In `bootstrap/app.php` (Laravel 11) or `app/Http/Kernel.php` (Laravel 10):

Laravel 11 — add to bootstrap/app.php:
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \App\Http\Middleware\RoleMiddleware::class,
    ]);
})
```

Laravel 10 — add to $routeMiddleware in Kernel.php:
```php
'role' => \App\Http\Middleware\RoleMiddleware::class,
```

### 6. Run Migrations & Seed
```bash
php artisan migrate
php artisan db:seed
```

### 7. Create Storage Link
```bash
php artisan storage:link
```

### 8. Run the Application
```bash
php artisan serve
```

Visit: http://localhost:8000

---

## Default Credentials

| Role       | Email                        | Password        |
|------------|------------------------------|-----------------|
| Admin      | admin@eventtrack.com         | Admin@1234      |
| Organizer  | organizer@eventtrack.com     | Organizer@1234  |
| Attendee   | attendee@eventtrack.com      | Attendee@1234   |

---

## Key Laravel Concepts Used
- **MVC** — Models, Views (Blade), Controllers
- **Eloquent ORM** — Model relationships & queries
- **Route Groups** — with prefix, name, and middleware
- **Form Requests** — StoreEventRequest for validation
- **Blade Templates** — layouts, partials, @yield/@section/@stack
- **Middleware** — RoleMiddleware for role-based access
- **Storage** — QR codes, certificates, banners in storage/app/public
- **Seeders** — Default admin, categories, venues
- **Migrations** — Database schema version control
