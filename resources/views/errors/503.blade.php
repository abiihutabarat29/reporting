<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Maintenance - Website Sedang Dalam Pemeliharaan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background-color: #f7f7f7;
            color: #444;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            text-align: center;
            padding: 50px;
        }

        .container {
            max-width: 600px;
            margin: auto;
        }

        h1 {
            font-size: 30px;
            color: #c0392b;
        }

        p {
            font-size: 18px;
            line-height: 1.6;
        }

        .logo {
            width: 100px;
            margin-top: 80px
        }
    </style>
</head>

<body>
    <div class="container">
        <img src="{{ asset('assets/img/LOGO_PKK_PNG.png') }}" alt="Logo" class="logo">

        <h1>Aplikasi Sedang Dalam Pemeliharaan</h1>
        <p>Mohon maaf atas ketidaknyamanannya. Kami sedang melakukan pemeliharaan sistem untuk meningkatkan layanan
            kami.</p>
        <p>Silakan kembali lagi nanti.</p>

        <p style="margin-top: 40px; font-size: 14px; color: #aaa;">
            &copy; {{ date('Y') }} E-Reporting Kabupaten Batu Bara.
        </p>
    </div>
</body>

</html>
