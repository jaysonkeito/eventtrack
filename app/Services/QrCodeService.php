<?php

namespace App\Services;

use App\Models\Registration;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeService
{
    /**
     * Generate a QR code PNG for a registration and save it to storage.
     * Uses: simplesoftwareio/simple-qrcode (install via composer)
     *   composer require simplesoftwareio/simple-qrcode
     */
    public function generate(Registration $registration): string
    {
        $filename = 'qr_' . $registration->registration_code . '.png';
        $path     = 'qrcodes/' . $filename;

        // Generate QR as PNG and store in public disk
        $qrImage = QrCode::format('png')
            ->size(300)
            ->errorCorrection('H')
            ->generate($registration->registration_code);

        Storage::disk('public')->put($path, $qrImage);

        // Save path to registration
        $registration->update(['qr_code_path' => $filename]);

        return $filename;
    }

    /**
     * Delete QR code file for a registration.
     */
    public function delete(Registration $registration): void
    {
        if ($registration->qr_code_path) {
            Storage::disk('public')->delete('qrcodes/' . $registration->qr_code_path);
        }
    }
}
