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
                            <h1 style="margin: 0; font-size: 22px; font-weight: 600;">Kasly</h1>
                            <p style="margin: 6px 0 0; font-size: 14px; opacity: 0.85;">Pemberitahuan Akun Pengurus Baru</p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 32px;">
                            <p style="font-size: 15px; margin: 0 0 16px;">Halo <strong>{{ $staffName }}</strong>,</p>
                            <p style="font-size: 14px; line-height: 1.6; margin: 0 0 24px; color: #4A6583;">
                                Organisasi <strong>{{ $orgName }}</strong> (Kode: <strong>{{ $orgCode }}</strong>) telah resmi didaftarkan di sistem manajemen kas <strong>Kasly</strong>. Akun Staff pengelola awal Anda telah siap digunakan.
                            </p>

                            <!-- Credentials Box -->
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #DDEBFC; border-radius: 10px; margin-bottom: 24px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin: 0 0 8px; font-size: 13px; color: #4A6583;">Rincian Kredensial Login:</p>
                                        <p style="margin: 4px 0; font-size: 14px;"><strong>Peran:</strong> Staff Pengelola Kas</p>
                                        <p style="margin: 4px 0; font-size: 14px;"><strong>Email:</strong> {{ $email }}</p>
                                        <p style="margin: 4px 0; font-size: 14px;"><strong>Password Sementara:</strong> {{ $password }}</p>
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
                                Harap segera mengganti kata sandi Anda setelah berhasil login pertama kali demi keamanan akun.
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

