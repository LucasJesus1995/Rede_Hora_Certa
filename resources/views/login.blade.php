<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-126464304-2"></script>
  <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-126464304-2');
  </script>

  <meta charset="utf-8" />
  <title>Administrador</title>
  <meta name="description" content="app, web app, responsive, responsive layout, admin, admin panel, admin dashboard, flat, flat ui, ui kit, AngularJS, ui route, charts, widgets, components" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

  <link rel="stylesheet" href="/structure/layout/material/libs/assets/animate.css/animate.css" type="text/css" />
  <link rel="stylesheet" href="/structure/layout/material/libs/assets/font-awesome/css/font-awesome.css" type="text/css" />
  <link rel="stylesheet" href="/structure/layout/material/libs/jquery/bootstrap/dist/css/bootstrap.css" type="text/css" />
  <link rel="stylesheet" href="/structure/layout/material/libs/jquery/waves/dist/waves.css" type="text/css" />

  <link rel="stylesheet" href="/structure/layout/material/styles/material-design-icons.css" type="text/css" />
  <link rel="stylesheet" href="/structure/layout/material/styles/font.css" type="text/css" />
  <link rel="stylesheet" href="/structure/layout/material/styles/app.css" type="text/css" />
  <link rel="stylesheet" href="/src/css/app.css?{{NCACHE}}" type="text/css" />
  <link rel="stylesheet" href="/src/plugins/chosen/chosen.min.css?{{NCACHE}}" type="text/css" />
  <link rel="shortcut icon" type="image/x-icon" href="/src/image/ico/favicon.ico">

</head>
<body>
<div class="app">
@yield('content')
</div>

<script src="/structure/layout/material/libs/jquery/jquery/dist/jquery.js"></script>
<script src="/structure/layout/material/libs/jquery/bootstrap/dist/js/bootstrap.js"></script>
<script src="/structure/layout/material/libs/jquery/waves/dist/waves.js"></script>

<script src="/structure/layout/material/scripts/ui-load.js"></script>
<script src="/structure/layout/material/scripts/ui-jp.config.js"></script>
<script src="/structure/layout/material/scripts/ui-jp.js"></script>
<script src="/structure/layout/material/scripts/ui-nav.js"></script>
<script src="/structure/layout/material/scripts/ui-toggle.js"></script>
<script src="/structure/layout/material/scripts/ui-waves.js"></script>

<script src="/src/plugins/chosen/chosen.jquery.js?{{NCACHE}}"></script>
<script src="/src/js/login.js?{{NCACHE}}"></script>

</body>
</html>
