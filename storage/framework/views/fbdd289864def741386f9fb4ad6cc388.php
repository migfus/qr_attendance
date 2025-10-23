<!DOCTYPE html>
<html >
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <meta
      name="keywords"
      content="drone, dji mini 2, Schwarzenegger Belonio, portfolio, web developer, assets, cinematic, videos, music, bgm, background music"
    />
    <meta
      name="description"
      content="Schwarzenegger R. Belonio (Migfus) portfolio website."
    />
    <link
      rel="icon"
      type="image/png"
      href="/ohrm.png"
    />
    <title>Loading...</title>

    <style>
        body {
            background-color: '#404040'
        }
    </style>


    <?php echo app('Illuminate\Foundation\Vite')('resources/js/app.ts'); ?>
    <?php echo app('Illuminate\Foundation\Vite')('resources/css/app.css'); ?>
    <?php echo app('Tighten\Ziggy\BladeRouteGenerator')->generate(); ?>
    <?php if (!isset($__inertiaSsrDispatched)) { $__inertiaSsrDispatched = true; $__inertiaSsrResponse = app(\Inertia\Ssr\Gateway::class)->dispatch($page); }  if ($__inertiaSsrResponse) { echo $__inertiaSsrResponse->head; } ?>
  </head>
  <body ref="infinite">
    <?php if (!isset($__inertiaSsrDispatched)) { $__inertiaSsrDispatched = true; $__inertiaSsrResponse = app(\Inertia\Ssr\Gateway::class)->dispatch($page); }  if ($__inertiaSsrResponse) { echo $__inertiaSsrResponse->body; } else { ?><div id="app" data-page="<?php echo e(json_encode($page)); ?>"></div><?php } ?>
  </body>
</html>
<?php /**PATH /home/u218070332/domains/qr.cmuohrm.site/public_html/resources/views/app.blade.php ENDPATH**/ ?>