<!DOCTYPE html>
<html >
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
        <meta
            name="keywords"
            content="ohrm, qr attendance, cmu, cmu ohrm, qr, attendance."
        />
        <meta
            name="description"
            content="OHRM QR Attendance, Office of Human Resource Management Quick Response Attendance."
        />
        <link
            rel="icon"
            type="image/png"
            href="{{ env('APP_URL', 'https://qr.cmuohrm.site') }}/images/ohrm.png"
        />
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&display=swap" rel="stylesheet">

        <title>OHRM QR Attendance</title>

        <style>
            body {
                background-color: '#191a1b'
            }
        </style>


        @vite('resources/js/app.ts')
        @vite('resources/css/app.css')
        @routes
        @inertiaHead
    </head>
    <body ref="infinite" class="dark:bg-dark-001">
        @inertia
    </body>
</html>
