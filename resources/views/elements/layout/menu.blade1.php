<!--
1 - Enfermagem
2 - Medico
3 - Administrador
4 - Recepcao
//-->
<div class="left">
<div class="box bg-white">
  <div class="navbar md-whiteframe-z1 no-radius green">
      <a class="navbar-brand" style="text-align: center">
        <img src="/src/image/logo/cies-horizontal-white.png" alt="Hora Certa" style="max-height: 45px; margin: auto; margin-top: 8px; ">
      </a>
  </div>
  <div class="box-row">
    <div class="box-cell scrollable hover">
      <div class="box-inner">
        <div class="p hidden-folded green-50" style="background-image:url(/structure/layout/material/images/bg.png); background-size:cover">
          <div class="rounded w-64 bg-white inline pos-rlt">
            <img src="/structure/layout/material/images/a0.jpg" class="img-responsive rounded">
          </div>
          <a class="block m-t-sm" ui-toggle-class="hide, show" target="#nav, #account">
            <span class="block font-bold">{{Auth::user()->name}}</span>
            {{ Auth::user()->email}}<br />
            <span class="block font-bold label bg-danger">{{\App\Http\Helpers\Util::Perfil(Auth::user()->profile)}}</span>
          </a>
        </div>
        <div id="nav">
          <nav ui-nav>
              <ul class="nav" style="margin-top: -10px;">
                <li class="nav-header m-v-sm hidden-folded green-50"> {{Lang::get('app.agendamento')}}</li>
                <li>
                  <a md-ink-ripple>
                    <span class="pull-right text-muted">
                      <i class="fa fa-caret-down"></i>
                    </span>
                    <i class="glyphicon glyphicon-calendar"></i>
                    <span class="font-normal">&nbsp; {{Lang::get('app.agendamento')}}</span>
                  </a>
                  <ul class="nav nav-sub">
                    <li><a md-ink-ripple href="/admin/agendas">{{Lang::get('app.agenda')}}</a></li>
                    @if(in_array(Auth::user()->profile, array(1,3,4)))
                    <li><a md-ink-ripple href="/admin/pacientes">{{Lang::get('app.pacientes')}}</a></li>
                    @endif
                    <li><a md-ink-ripple href="/admin/laudo-medico">{{Lang::get('app.laudo-medico')}}</a></li>
                  </ul>
                </li>
                <li class="nav-header m-v-sm hidden-folded green-50"> {{Lang::get('app.bi')}}</li>
                <li>
                  <a md-ink-ripple>
                    <span class="pull-right text-muted">
                      <i class="fa fa-caret-down"></i>
                    </span>
                    <i class="glyphicon glyphicon-graph"></i>
                    <span class="font-normal">&nbsp; {{Lang::get('app.relatorios')}}</span>
                  </a>
                  <ul class="nav nav-sub">
                    <li><a md-ink-ripple href="/admin/relatorio/procedimentos">{{Lang::get('app.procedimentos')}}</a></li>
                    <li><a md-ink-ripple href="/admin/relatorio/bpa">BPA</a></li>
                    <li><a md-ink-ripple href="/admin/relatorio/producao">Produção</a></li>
                  </ul>
                </li>
                <li class="nav-header m-v-sm hidden-folded green-50">{{Lang::get('app.configuracao')}}</li>
                <li>
                  <a md-ink-ripple>
                    <span class="pull-right text-muted">
                      <i class="fa fa-caret-down"></i>
                    </span>
                    <i class="glyphicon glyphicon-folder-open"></i>
                    <span class="font-normal">&nbsp; {{Lang::get('app.cadastro')}}</span>
                  </a>
                  <ul class="nav nav-sub">
                    {{--<li><a md-ink-ripple href="/admin/tipos">{{Lang::get('app.tipos')}}</a></li>--}}
                    <li><a md-ink-ripple href="/admin/unidades">{{Lang::get('app.unidades')}}</a></li>
                    <li><a md-ink-ripple href="/admin/insumos">{{Lang::get('app.insumos')}}</a></li>
                    <li><a md-ink-ripple href="/admin/arenas">{{Lang::get('app.arenas')}}</a></li>
                    <li><a md-ink-ripple href="/admin/empresas">{{Lang::get('app.empresas')}}</a></li>
                    <li><a md-ink-ripple href="/admin/pais">{{Lang::get('app.pais')}}</a></li>
                    <li><a md-ink-ripple href="/admin/linha-cuidado">{{Lang::get('app.linha-cuidado')}}</a></li>
                    <li><a md-ink-ripple href="/admin/procedimentos">{{Lang::get('app.procedimentos')}}</a></li>
                    <li><a md-ink-ripple href="/admin/cbo">{{Lang::get('app.cbo')}}</a></li>
                    <li><a md-ink-ripple href="/admin/profissionais">{{Lang::get('app.profissionais')}}</a></li>
                    <li><a md-ink-ripple href="/admin/medicamentos">{{Lang::get('app.medicamentos')}}</a></li>
                    <li><a md-ink-ripple href="/admin/estabelecimento">{{Lang::get('app.estabelecimento')}}</a></li>
                  </ul>
                </li>
                <li>
                <a md-ink-ripple>
                    <span class="pull-right text-muted">
                      <i class="fa fa-caret-down"></i>
                    </span>
                    <i class=" mdi-action-settings i-20"></i>
                    <span class="font-normal">{{Lang::get('app.manutencao')}}</span>
                  </a>
                  <ul class="nav nav-sub">
                    <li><a md-ink-ripple href="/admin/anamnese-perguntas">{{Lang::get('app.anamnese-perguntas')}}</a></li>
                    <li><a md-ink-ripple href="/admin/perfil">{{Lang::get('app.perfil.usuarios')}}</a></li>
                    <li><a md-ink-ripple href="/admin/usuarios">{{Lang::get('app.usuarios')}}</a></li>
                  </ul>
                </li>
<li>
                <a md-ink-ripple>
                    <span class="pull-right text-muted">
                      <i class="fa fa-caret-down"></i>
                    </span>
                    <i class=" mdi-action-system-update-tv i-20"></i>
                    <span class="font-normal">{{Lang::get('app.importacao')}}</span>
                  </a>
                  <ul class="nav nav-sub">
                    <li><a md-ink-ripple href="/admin/importacao/agenda">{{Lang::get('app.agenda')}}</a></li>
                  </ul>
                </li>

                <li class="b-b b m-v-sm"></li>
                <li>
                    <a class=" waves-effect" md-ink-ripple="" href="/logout">
                      <i class="icon mdi-action-exit-to-app i-20"></i>
                      <span>Sair</span>
                    </a>
                </li>
              </ul>
            </nav>
           </div>
          <div id="account" class="hide m-v-xs">
          <nav>
            <ul class="nav">
              <li>
                <a md-ink-ripple href="/logout">
                  <i class="icon mdi-action-exit-to-app i-20"></i>
                  <span>{{Lang::get('app.sair')}}</span>
                </a>
              </li>
              <li class="m-v-sm b-b b"></li>
              <li>
                <div class="nav-item" ui-toggle-class="folded" target="#aside">
                  <label class="md-check">
                    <input type="checkbox">
                    <i class="purple no-icon"></i>
                    <span class="hidden-folded">{{Lang::get('app.ocultar-menu')}}</span>
                  </label>
                </div>
              </li>
            </ul>
          </nav>
        </div>

      </div>
    </div>
  </div>
</div>
</div>