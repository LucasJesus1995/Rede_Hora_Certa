<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <link rel="stylesheet" href="/structure/layout/material/libs/jquery/bootstrap/dist/css/bootstrap.css" type="text/css" />
  <link rel="stylesheet" href="/structure/layout/material/styles/material-design-icons.css" type="text/css" />
  <link rel="stylesheet" href="/structure/layout/material/styles/font.css" type="text/css" />
  <link rel="stylesheet" href="/structure/layout/material/styles/app.css" type="text/css" />
  <link rel="stylesheet" href="/src/css/app.css" type="text/css" />
  <link rel="stylesheet" href="/src/css/impressao.css" type="text/css" media="all" />
  <link rel="stylesheet" href="/src/css/impressao-print.css" type="text/css" media="screen|print" />
</head>
<body style="">
@yield('content')

<script>
   window.print();
</script>
</body>
</html>


