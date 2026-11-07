<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Pemberitahuan Akun Pengurus Kasly</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #DDEBFC; color: #2F4862; margin: 0; padding: 24px;">

    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td align="center">
                <table width="600" border="0" cellspacing="0" cellpadding="0" style="background-color: #FFFFFF; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 16px rgba(47, 72, 98, 0.08);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #2F4862; padding: 24px 32px; color: #FFFFFF;">
                            <div style="font-size: 24px; font-weight: bold; letter-spacing: -0.5px; color: #FFFFFF; margin-bottom: 4px;">
                                Kasly
                            </div>
                            <p style="margin: 4px 0 0; font-size: 14px; opacity: 0.85;">Pemberitahuan Akun Pengurus Baru</p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 32px;">
                            <p style="font-size: 15px; margin: 0 0 16px;">Halo <strong>{{ $staffName }}</strong>,</p>
                            <p style="font-size: 14px; line-height: 1.6; margin: 0 0 24px; color: #4A6583;">
                                Organisasi <strong>{{ $orgName }}</strong> telah resmi didaftarkan di sistem manajemen kas <strong>Kasly</strong>. Akun Staff pengelola awal Anda telah siap digunakan.
                            </p>

                            <!-- Credentials Box -->
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #DDEBFC; border-radius: 10px; margin-bottom: 24px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin: 0 0 12px; font-size: 13px; font-weight: 600; color: #2F4862;">Rincian Kredensial Login:</p>
                                        <table width="100%" border="0" cellspacing="0" cellpadding="4" style="font-size: 14px; color: #2F4862;">
                                            <tr>
                                                <td width="160" style="color: #4A6583;">Kode Organisasi:</td>
                                                <td><code style="font-family: monospace; font-size: 16px; font-weight: bold; color: #2F4862; background-color: #FFFFFF; padding: 2px 8px; border-radius: 4px;">{{ $orgCode }}</code></td>
                                            </tr>
                                            <tr>
                                                <td style="color: #4A6583;">Peran:</td>
                                                <td><strong>Staff Pengelola Kas</strong></td>
                                            </tr>
                                            <tr>
                                                <td style="color: #4A6583;">Alamat Email:</td>
                                                <td><strong>{{ $email }}</strong></td>
                                            </tr>
                                            <tr>
                                                <td style="color: #4A6583;">Password Sementara:</td>
                                                <td><code style="font-family: monospace; font-size: 16px; font-weight: bold; color: #2F4862; background-color: #FFFFFF; padding: 2px 8px; border-radius: 4px;">{{ $password }}</code></td>
                                            </tr>
                                        </table>
                                        <p style="margin: 12px 0 0; font-size: 12px; color: #4A6583;">
                                            *Pada halaman login Kasly, pilih tab <strong>Pengurus / Staff</strong> lalu masukkan <strong>Kode Organisasi</strong> di atas beserta email dan password Anda.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA Button -->
                            <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 24px;">
                                <tr>
                                    <td align="center" style="border-radius: 8px; background-color: #2F4862;">
                                        <a href="{{ $loginUrl }}" target="_blank" style="font-size: 14px; font-weight: 600; color: #FFFFFF; text-decoration: none; padding: 12px 24px; display: inline-block;">
                                            Masuk ke Portal Pengurus
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size: 13px; line-height: 1.6; color: #4A6583; margin: 0;">
                                Demi keamanan, Anda dapat mengganti kata sandi setelah berhasil masuk pertama kali.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #F8FAFC; padding: 16px 32px; border-top: 1px solid #B5CCEB; font-size: 12px; color: #4A6583; text-align: center;">
                            &copy; {{ date('Y') }} Kasly. Sistem Kas Organisasi Transparan.
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
