        <?php
            $digitadora = \App\Http\Helpers\Util::getDataDigitadora();
            $contrato = \App\User::getContrato();
        ?>

<div class="navbar md-whiteframe-z1 no-radius green">
    <a class="navbar-item pull-left visible-xs visible-sm waves-effect" data-target="#aside" data-toggle="modal" md-ink-ripple=""><i class="mdi-navigation-menu i-24"></i></a>

    <div class="navbar-item pull-left h4">{{(!is_null($contrato)) ? $contrato->nome : null }}</div>

    <div ui-view="navbar" class="pull-right">

        @if(!empty($digitadora['doctor']))
            <?php
                $medico = \App\Profissionais::getMedicoByID($digitadora['doctor']);
            ?>
            @if(!empty($medico->nome))
                <h3 class="margin5">{!! $medico->nome !!}</h3>
            @endif
        @endif
    </div>


    <div class="pos-abt w-full h-full indigo hide" id="search">
      <div class="box">
        <div class="box-col w-56 text-center">
          <a target="#search" ui-toggle-class="show" class="navbar-item inline waves-effect" md-ink-ripple=""><i class="mdi-navigation-arrow-back i-24"></i></a>
        </div>
        <div class="box-col v-m">
          <input ng-model="app.search.content" placeholder="Search" class="form-control input-lg no-bg no-border">
        </div>
        <div class="box-col w-56 text-center">
          <a class="navbar-item inline waves-effect" md-ink-ripple=""><i class="mdi-av-mic i-24"></i></a>
        </div>
      </div>
    </div>
  </div>

    @if(ENV_SISTEMA == "TESTE")
        <div class="row">
            <div class="btn btn-fw btn-danger waves-effect waves-effect col-md-12">{!! \App\Http\Helpers\Util::getUserName() !!}, você está em um ambiente de teste. Todas as informações alteradas serão apagadas no dia seguinte.</div>
        </div>
    @endif

    <div class="p-h-md p-v bg-white box-shadow pos-rlt">
        @if(!empty($title))
            <h3 class="no-margin">{{Lang::get($title)}}</h3>
        @endif
    </div>