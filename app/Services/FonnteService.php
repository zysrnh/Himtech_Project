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

            Log::warning("Fonnte WA Gagal Terkirim ke [{$target}]: ".$response->body());

            return false;
        } catch (\Throwable $e) {
            Log::error("Fonnte WA Exception ke [{$target}]: ".$e->getMessage());

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
        string $password,
        ?string $username = null
    ): bool {
        $loginUrl = url('/login');

        $usernameLine = ! empty($username) ? "• Username: *{$username}*\n" : '';

        $message = "*PEMBERITAHUAN AKUN PENGURUS KASLY*\n\n"
            ."Halo {$staffName},\n"
            ."Organisasi *{$orgName}* ({$orgCode}) telah resmi didaftarkan di sistem Kasly.\n\n"
            ."Berikut rincian akun Staff Anda:\n"
            ."• Kode Organisasi: *{$orgCode}*\n"
            .$usernameLine
            ."• Email Login: {$email}\n"
            ."• Password Sementara: {$password}\n"
            ."• Tautan Login: {$loginUrl}\n\n"
            .'Pada halaman login Kasly, masukkan Kode Organisasi di atas beserta username atau email dan password Anda. Terima kasih.';

        return self::send($phone, $message);
    }

    /**
     * Kirim notifikasi tanda terima resmi pelunasan kas ke WhatsApp anggota (Saat Lunas).
     */
    public static function sendPaymentSuccess(
        string $phone,
        string $memberName,
        string $orgName,
        string $periodTitle,
        float $amount,
        string $paidDate,
        string $method = 'Tunai / Cash',
        bool $isOnTime = true
    ): bool {
        $nominalFormatted = 'Rp '.number_format($amount, 0, ',', '.');
        $statusDisiplin = $isOnTime ? 'Tepat Waktu' : 'Terlambat';
        $loginUrl = url('/login');

        $message = "*TANDA TERIMA RESMI PEMBAYARAN KAS*\n"
            ."*{$orgName}*\n\n"
            ."Halo {$memberName},\n"
            ."Pembayaran iuran kas Anda telah berhasil diverifikasi *LUNAS*.\n\n"
            ."*Rincian Transaksi:*\n"
            ."• Periode: {$periodTitle}\n"
            ."• Nominal: *{$nominalFormatted}*\n"
            ."• Tanggal Bayar: {$paidDate}\n"
            ."• Saluran Pembayaran: {$method}\n"
            ."• Status Ketepatan Waktu: *{$statusDisiplin}*\n\n"
            ."Catatan mutasi ini sudah otomatis dibukukan secara transparan ke Buku Kas Organisasi.\n"
            ."Cek riwayat kas pribadi Anda di: {$loginUrl}\n\n"
            ."Terima kasih atas partisipasi aktif dan kedisiplinan Anda!\n"
            ."_Bendahara {$orgName}_";

        return self::send($phone, $message);
    }

    /**
     * Kirim pengingat tagihan kas resmi menjelang jatuh tempo (H-3, H-1, atau hari H).
     */
    public static function sendDuesReminder(
        string $phone,
        string $memberName,
        string $orgName,
        string $periodTitle,
        float $amount,
        string $dueDate,
        ?int $daysLeft = null
    ): bool {
        $nominalFormatted = 'Rp '.number_format($amount, 0, ',', '.');
        $loginUrl = url('/login');

        $headerInfo = 'Mendekati Batas Jatuh Tempo';
        if ($daysLeft !== null) {
            if ($daysLeft > 0) {
                $headerInfo = "H-{$daysLeft} Menjelang Jatuh Tempo";
            } elseif ($daysLeft === 0) {
                $headerInfo = 'HARI INI Terakhir Jatuh Tempo';
            } else {
                $daysOver = abs($daysLeft);
                $headerInfo = "Telat {$daysOver} Hari dari Jatuh Tempo";
            }
        }

        $message = "*PENGINGAT RESMI IURAN KAS ({$headerInfo})*\n"
            ."*{$orgName}*\n\n"
            ."Halo {$memberName},\n"
            ."Kami mengingatkan kembali kewajiban iuran kas ormawa Anda yang belum dilunasi.\n\n"
            ."*Detail Tagihan:*\n"
            ."• Periode: {$periodTitle}\n"
            ."• Total Kewajiban: *{$nominalFormatted}*\n"
            ."• Batas Jatuh Tempo: *{$dueDate}*\n"
            ."• Status: *Belum Lunas*\n\n"
            ."Yuk segera lunasi sebelum tanggal jatuh tempo agar penilaian kedisiplinan iuran Anda tercatat *Tepat Waktu*.\n\n"
            ."Pembayaran dapat dilakukan tunai ke Bendahara atau instan via QRIS / Virtual Account di:\n"
            ."{$loginUrl}\n\n"
            ."_Salam hangat,_\n"
            ."_Pengurus & Bendahara {$orgName}_";

        return self::send($phone, $message);
    }
}
