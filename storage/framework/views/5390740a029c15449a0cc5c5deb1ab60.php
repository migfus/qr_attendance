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
            href="<?php echo e(env('APP_URL', 'https://qr.cmuohrm.site')); ?>/images/ohrm.png"
        />
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&display=swap" rel="stylesheet">

        <title>OHRM QR Attendance</title>

        <style>
            body {
                background-color: '#191a1b'
            }
        </style>


        <?php echo app('Illuminate\Foundation\Vite')('resources/js/app.ts'); ?>
        <?php echo app('Illuminate\Foundation\Vite')('resources/css/app.css'); ?>
        <?php echo app('Tighten\Ziggy\BladeRouteGenerator')->generate(); ?>
        <?php if (!isset($__inertiaSsrDispatched)) { $__inertiaSsrDispatched = true; $__inertiaSsrResponse = app(\Inertia\Ssr\Gateway::class)->dispatch($page); }  if ($__inertiaSsrResponse) { echo $__inertiaSsrResponse->head; } ?>
    </head>
    <body ref="infinite" class="dark:bg-dark-001">
        <?php if (!isset($__inertiaSsrDispatched)) { $__inertiaSsrDispatched = true; $__inertiaSsrResponse = app(\Inertia\Ssr\Gateway::class)->dispatch($page); }  if ($__inertiaSsrResponse) { echo $__inertiaSsrResponse->body; } else { ?><div id="app" data-page="<?php echo e(json_encode($page)); ?>"></div><?php } ?>
    </body>
</html>
<?php /**PATH D:\@Projects\2025\Web\CMU Projects\OHRM QR\qr_attendance_v2\resources\views/app.blade.php ENDPATH**/ ?>