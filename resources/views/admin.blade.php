<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-126464304-2"></script>
  <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('set', {'user_id': '<?php echo \App\User::getId() ?>'});
      gtag('config', 'UA-126464304-2');
  </script>

  <meta charset="utf-8" />
  <title>{{Lang::get('app.administrador')}}</title>
  <meta name="description" content="app, web app, responsive, responsive layout, admin, admin panel, admin dashboard, flat, flat ui, ui kit, AngularJS, ui route, charts, widgets, components" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

  <link rel="stylesheet" href="/src/plugins/jquery-ui/jquery-ui.min.css?{{NCACHE}}" type="text/css" />
  <link rel="stylesheet" href="/structure/layout/material/libs/assets/animate.css/animate.css?{{NCACHE}}" type="text/css" />
  <link rel="stylesheet" href="/structure/layout/material/libs/assets/font-awesome/css/font-awesome.css?{{NCACHE}}" type="text/css" />
  <link rel="stylesheet" href="/structure/layout/material/libs/jquery/bootstrap/dist/css/bootstrap.css?{{NCACHE}}" type="text/css" />
  <link rel="stylesheet" href="/structure/layout/material/libs/jquery/waves/dist/waves.css?{{NCACHE}}" type="text/css" />
  

  <link rel="stylesheet" href="/structure/layout/material/styles/material-design-icons.css?{{NCACHE}}" type="text/css" />
  <link rel="stylesheet" href="/structure/layout/material/styles/font.css?{{NCACHE}}" type="text/css" />
  <link rel="stylesheet" href="/structure/layout/material/styles/app.css?{{NCACHE}}" type="text/css" />
  <link rel="stylesheet" href="/src/css/app.css?{{NCACHE}}" type="text/css" />
  <link rel="stylesheet" href="/src/plugins/chosen/chosen.min.css?{{NCACHE}}" type="text/css" />
  <link rel="stylesheet" href="/src/plugins/datepicker/css/bootstrap-datepicker.css?{{NCACHE}}" type="text/css" />
  <link rel="shortcut icon" type="image/x-icon" href="/src/image/ico/favicon.ico" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css?{{NCACHE}}" />
  <link href="/src/plugins/formstone/dist/css/upload.css?{{NCACHE}}" rel="stylesheet">

  {{--{!! Charts::assets() !!}--}}

</head>
<body>
<div class="app">

  <aside id="aside" class="app-aside modal fade " role="menu">
    @include('elements.layout.menu')
  </aside>

  <div id="content" class="app-content" role="main">
    <div class="box">
    @include('elements.layout.navbar')

    <div class="box-row">
        <div class="box-cell">
          <div class="box-inner padding">
            @yield('content')
          </div>
        </div>
    </div>

    </div>
  </div>

  <div class="modal fade" id="user" data-backdrop="false">
    <div class="right w-xl bg-white md-whiteframe-z2">
        <div class="box">
    <div class="p p-h-md">
      <data-dismiss="modal" class="pull-right text-muted-lt text-2x m-t-n inline p-sm">&times;</a>
      <strong>Members</strong>
    </div>
    <div class="box-row">
      <div class="box-cell">
        <div class="box-inner">
          <div class="list-group no-radius no-borders">
            <a data-toggle="modal" data-target="#chat" data-dismiss="modal"  class="list-group-item p-h-md">
              <img src="/structure/layout/material/images/a1.jpg" class="pull-left w-40 m-r img-circle">
              <div class="clear">
                <span class="font-bold block">Jonathan Doe</span>
                <span class="clear text-ellipsis text-xs">"Hey, What's up"</span>
              </div>
            </a>
            <a data-toggle="modal" data-target="#chat" data-dismiss="modal"  class="list-group-item p-h-md">
              <img src="/structure/layout/material/images/a2.jpg" class="pull-left w-40 m-r img-circle">
              <div class="clear">
                <span class="font-bold block">James Pill</span>
                <span class="clear text-ellipsis text-xs">"Lorem ipsum dolor sit amet onsectetur adipiscing elit"</span>
              </div>
            </a>
            <div class="p-h-md m-t p-v-xs">Work</div>
            <a data-toggle="modal" data-target="#chat" data-dismiss="modal"  class="list-group-item p-h-md">
                <i class="fa fa-circle text-success text-xs m-r-xs"></i>
                <span>Jonathan Morina</span>
            </a>
            <a data-toggle="modal" data-target="#chat" data-dismiss="modal"  class="list-group-item p-h-md">
                <i class="fa fa-circle text-success text-xs m-r-xs"></i>
                <span>Mason Yarnell</span>
            </a>
            <a data-toggle="modal" data-target="#chat" data-dismiss="modal"  class="list-group-item p-h-md">
                <i class="fa fa-circle text-warning text-xs m-r-xs"></i>
                <span>Mike Mcalidek</span>
            </a>
            <a data-toggle="modal" data-target="#chat" data-dismiss="modal"  class="list-group-item p-h-md">
                <i class="fa fa-circle text-muted-lt text-xs m-r-xs"></i>
                <span>Cris Labiso</span>
            </a>
            <a data-toggle="modal" data-target="#chat" data-dismiss="modal"  class="list-group-item p-h-md">
                <i class="fa fa-circle text-muted-lt text-xs m-r-xs"></i>
                <span>Daniel Sandvid</span>
            </a>
            <a data-toggle="modal" data-target="#chat" data-dismiss="modal"  class="list-group-item p-h-md">
                <i class="fa fa-circle text-muted-lt text-xs m-r-xs"></i>
                <span>Helder Oliveira</span>
            </a>
            <a data-toggle="modal" data-target="#chat" data-dismiss="modal"  class="list-group-item p-h-md">
                <i class="fa fa-circle text-muted-lt text-xs m-r-xs"></i>
                <span>Jeff Broderik</span>
            </a>
            <a data-toggle="modal" data-target="#chat" data-dismiss="modal"  class="list-group-item p-h-md">
                <i class="fa fa-circle text-muted-lt text-xs m-r-xs"></i>
                <span>Daniel Sandvid</span>
            </a>
            <a data-toggle="modal" data-target="#chat" data-dismiss="modal"  class="list-group-item p-h-md">
                <i class="fa fa-circle text-muted-lt text-xs m-r-xs"></i>
                <span>Helder Oliveira</span>
            </a>
            <a data-toggle="modal" data-target="#chat" data-dismiss="modal"  class="list-group-item p-h-md">
                <i class="fa fa-circle text-muted-lt text-xs m-r-xs"></i>
                <span>Jeff Broderik</span>
            </a>
            <div class="p-h-md m-t p-v-xs">Partner</div>
            <a data-toggle="modal" data-target="#chat" data-dismiss="modal"  class="list-group-item p-h-md">
                <i class="fa fa-circle text-success text-xs m-r-xs"></i>
                <span>Mason Yarnell</span>
            </a>
            <a data-toggle="modal" data-target="#chat" data-dismiss="modal"  class="list-group-item p-h-md">
                <i class="fa fa-circle text-warning text-xs m-r-xs"></i>
                <span>Mike Mcalidek</span>
            </a>
            <a data-toggle="modal" data-target="#chat" data-dismiss="modal"  class="list-group-item p-h-md">
                <i class="fa fa-circle text-muted-lt text-xs m-r-xs"></i>
                <span>Cris Labiso</span>
            </a>
            <a data-toggle="modal" data-target="#chat" data-dismiss="modal"  class="list-group-item p-h-md">
                <i class="fa fa-circle text-muted-lt text-xs m-r-xs"></i>
                <span>Jonathan Morina</span>
            </a>
            <a data-toggle="modal" data-target="#chat" data-dismiss="modal"  class="list-group-item p-h-md">
                <i class="fa fa-circle text-muted-lt text-xs m-r-xs"></i>
                <span>Daniel Sandvid</span>
            </a>
            <a data-toggle="modal" data-target="#chat" data-dismiss="modal"  class="list-group-item p-h-md">
                <i class="fa fa-circle text-muted-lt text-xs m-r-xs"></i>
                <span>Helder Oliveira</span>
            </a>
          </div>
        </div>
      </div>
    </div>
    <div class="p-h-md p-v">
      <p>Invite People</p>
      <a href class="text-muted"><i class="fa fa-fw fa-twitter"></i> Twitter</a>
      <a href class="text-muted m-h"><i class="fa fa-fw fa-facebook"></i> Facebook</a>
    </div>
  </div>

    </div>
  </div>

  <div class="modal fade" id="chat" data-backdrop="false">
    <div class="right w-xxl bg-white md-whiteframe-z2">
        <div class="box">
    <div class="p p-h-md">
      <a data-dismiss="modal" class="pull-right text-muted-lt text-2x m-t-n inline p-sm">&times;</a>
      <strong>Chat</strong>
    </div>
    <div class="box-row bg-light lt">
      <div class="box-cell">
        <div class="box-inner">
          <div class="p-md">
            <div class="m-b">
              <a href class="pull-left w-40 m-r-sm"><img src="/structure/layout/material/images/a2.jpg" alt="..." class="w-full img-circle"></a>
              <div class="clear">
                <div class="p p-v-sm bg-warning inline r">
                  Hi John, What's up...
                </div>
                <div class="text-muted-lt text-xs m-t-xs"><i class="fa fa-ok text-success"></i> 2 minutes ago</div>
              </div>
            </div>
            <div class="m-b">
              <a href class="pull-right w-40 m-l-sm"><img src="/structure/layout/material/images/a3.jpg" class="w-full img-circle" alt="..."></a>
              <div class="clear text-right">
                <div class="p p-v-sm bg-info inline text-left r">
                  Lorem ipsum dolor soe rooke..
                </div>
                <div class="text-muted-lt text-xs m-t-xs">1 minutes ago</div>
              </div>
            </div>
            <div class="m-b">
              <a href class="pull-left w-40 m-r-sm"><img src="/structure/layout/material/images/a2.jpg" alt="..." class="w-full img-circle"></a>
              <div class="clear">
                <div class="p p-v-sm bg-warning inline r">
                  Good!
                </div>
                <div class="text-muted-lt text-xs m-t-xs"><i class="fa fa-ok text-success"></i> 5 seconds ago</div>
              </div>
            </div>
            <div class="m-b">
              <a href class="pull-right w-40 m-l-sm"><img src="/structure/layout/material/images/a3.jpg" class="w-full img-circle" alt="..."></a>
              <div class="clear text-right">
                <div class="p p-v-sm bg-info inline text-left r">
                  Dlor soe isep..
                </div>
                <div class="text-muted-lt text-xs m-t-xs">Just now</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="p-h-md p-v">
      <a class="pull-left w-32 m-r"><img src="/structure/layout/material/images/a3.jpg" class="w-full img-circle" alt="..."></a>
      <form>
        <div class="input-group">
          <input type="text" class="form-control" placeholder="Say something">
          <span class="input-group-btn">
            <button class="btn btn-default" type="button">SEND</button>
          </span>
        </div>
      </form>
    </div>
  </div>

    </div>
  </div>


</div>
@include('elements.layout.modal')

<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

<script type="text/javascript" src="/structure/layout/material/libs/jquery/jquery/dist/jquery.js?{{NCACHE}}"></script>
<script type="text/javascript" src="/src/plugins/jquery-ui/jquery-ui.min.js?{{NCACHE}}"></script>
<script type="text/javascript" src="/structure/layout/material/libs/jquery/bootstrap/dist/js/bootstrap.js?{{NCACHE}}"></script>
<script type="text/javascript" src="/structure/layout/material/libs/jquery/waves/dist/waves.js?{{NCACHE}}"></script>
<script type="text/javascript" src="/structure/layout/material/scripts/ui-load.js?{{NCACHE}}"></script>

<script type="text/javascript" src="/structure/layout/material/scripts/ui-nav.js?{{NCACHE}}"></script>
<script type="text/javascript" src="/structure/layout/material/scripts/ui-toggle.js?{{NCACHE}}"></script>
<script type="text/javascript" src="/structure/layout/material/scripts/ui-waves.js?{{NCACHE}}"></script>
<script type="text/javascript" src="/src/plugins/jquery.mask.js?{{NCACHE}}"></script>
<script type="text/javascript" src="/src/plugins/noty/js/noty/packaged/jquery.noty.packaged.min.js?{{NCACHE}}"></script>
<script type="text/javascript" src="/src/plugins/ckeditor/ckeditor.js?{{NCACHE}}"></script>
<script type="text/javascript" src="/src/plugins/ckeditor/adapters/jquery.js?{{NCACHE}}"></script>
<script type="text/javascript" src="/src/plugins/maskmoney/dist/jquery.maskMoney.min.js?{{NCACHE}}"></script>
<script type="text/javascript" src="/src/plugins/formstone/src/js/core.js?{{NCACHE}}"></script>
<script type="text/javascript" src="/src/plugins/formstone/dist/js/upload.js?{{NCACHE}}"></script>


<script type="text/javascript">
  var msg = new Object();
  msg['remove_registro'] = "<?php echo Lang::get('app.remove-registro'); ?>";
  msg['cidades_atualizada'] = "<?php echo Lang::get('app.cidades-atualizada'); ?>";
  msg['linha_cuidado_atualizado'] = "<?php echo Lang::get('app.linhas-cuidado-atualizada'); ?>";
  msg['profissionais_atualizado'] = "<?php echo Lang::get('app.profissionais-atualizado'); ?>";

  var _params = new Object();


  var user = new Object();
  user['nome'] = "<?php echo \App\Http\Helpers\Util::getUserName(); ?>"
  user['perfil'] = "<?php echo \App\Http\Helpers\Util::getNivel(); ?>"
  user['medico'] = "<?php echo \App\Http\Helpers\Util::getDataDigitadoraMedico(); ?>"

  $('textarea.ckeditor').ckeditor();
  $('textarea.ckeditor-simplificado').ckeditor();

  var token = "<?php echo csrf_token(); ?>";

CKEDITOR.config.toolbar = [
  ['Styles','Format','Font','FontSize','Source'],
  '/',
  ['Bold','Italic','Underline','StrikeThrough','-','Undo','Redo','-','Cut','Copy','Paste','-','Outdent','Indent'],
  ['NumberedList','BulletedList','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
  ['Image','Table','-','TextColor','BGColor']
] ;
</script>
<script type="text/javascript" src="/src/js/admin/atendimento.js?{{NCACHE}}"></script>
<script type="text/javascript" src="/src/plugins/chosen/chosen.jquery.js?{{NCACHE}}"></script>
<script type="text/javascript" src="/src/plugins/datepicker/js/bootstrap-datepicker.min.js?{{NCACHE}}"></script>
<script type="text/javascript" src="/src/plugins/datepicker/locales/bootstrap-datepicker.pt-BR.min.js?{{NCACHE}}"></script>
<script type="text/javascript" src="/src/js/app.js?{{NCACHE}}"></script>
<script type="text/javascript" src="/src/js/admin/cirurgico-index.js?{{NCACHE}}"></script>

<script src="https://d3js.org/d3.v5.min.js" charset="utf-8"></script>
<script src="/src/plugins/c3/c3.min.js"></script>

<script src="/src/plugins/jquery/jquery.form.js"></script>

<script type="text/javascript">
@yield('script')
</script>

</body>
</html>
