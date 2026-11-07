<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    /**
     * Kirim pesan WhatsApp menggunakan Fonnte API.
     */
    public static function send(string $target, string $message): bool
    {
        $token = config('services.fonnte.token');

        // Jika token belum diatur, catat ke log sistem agar tidak crash saat demo
        if (empty($token)) {
            Log::info("Fonnte WA Broadcast (Token Belum Diatur) ke [{$target}]:\n{$message}");
            return false;
        }

        try {
            // Bersihkan format nomor telepon ke standar internasional/lokal
            $cleanTarget = preg_replace('/[^0-9]/', '', $target);

            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->timeout(10)->post('https://api.fonnte.com/send', [
                'target' => $cleanTarget,
                'message' => $message,
                'countryCode' => '62',
            ]);

            if ($response->successful()) {
                Log::info("Fonnte WA Berhasil Terkirim ke [{$target}]");
                return true;
            }

            Log::warning("Fonnte WA Gagal Terkirim ke [{$target}]: " . $response->body());
            return false;
        } catch (\Throwable $e) {
            Log::error("Fonnte WA Exception ke [{$target}]: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Format dan kirim pesan kredensial akun staff baru.
     */
    public static function sendStaffAccountCreated(
        string $phone,
        string $staffName,
        string $orgName,
        string $orgCode,
        string $email,
        string $password
    ): bool {
        $loginUrl = url('/login?role=pengurus');

        $message = "*PEMBERITAHUAN AKUN PENGURUS KASLY*\n\n"
            . "Halo {$staffName},\n"
            . "Organisasi *{$orgName}* ({$orgCode}) telah resmi didaftarkan di sistem Kasly.\n\n"
            . "Berikut rincian akun Staff Anda:\n"
            . "• Peran: Staff Pengelola Kas\n"
            . "• Email Login: {$email}\n"
            . "• Password Sementara: {$password}\n"
            . "• Tautan Login: {$loginUrl}\n\n"
            . "Silakan login dan amankan kata sandi Anda setelah masuk. Terima kasih.";

        return self::send($phone, $message);
    }
}

