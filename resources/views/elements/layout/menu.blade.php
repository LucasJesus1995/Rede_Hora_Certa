<?php

use App\Roles;
use App\User;

$menu_presidencial_usuarios_acessso = config('cies.menu-presidencial-usuarios-acessso');
?>

<div class="left">
    <div class="box bg-white">
        <div class="navbar md-whiteframe-z1 no-radius green">
            <a class="navbar-brand" style="text-align: center">
                <img src="/src/image/logo/cies-horizontal-white.png" alt="Hora Certa"
                     style="max-height: 45px; margin: auto; margin-top: 8px; ">
            </a>
        </div>
        <div class="box-row">
            <div class="box-cell scrollable hover">
                <div class="box-inner">
                    <div class="p hidden-folded green-50"
                         style="background-image:url(/structure/layout/material/images/bg.png); background-size:cover">
                        <div class="rounded w-64 bg-white inline pos-rlt">
                            <i class="glyphicon glyphicon-user"
                               style="margin: 10px 12px; font-size: 40px; color: #CCC"></i>
                        </div>
                        <a class="block m-t-sm" ui-toggle-class="hide, show" target="#nav, #account">
                            <span class="block font-bold">{{Auth::user()->name}}</span>
                            {{ Auth::user()->email}}<br/>
                            <span class="block font-bold label bg-danger uppercase">{{ \App\Roles::get(Auth::user()->level)->role_title }}</span>
                        </a>
                    </div>
                    <div id="nav">
                        <nav ui-nav>

                            <ul class="nav" style="margin-top: -10px;">
                                <li class="nav-header m-v-sm hidden-folded green-50"> Atendimentos</li>
                                <li>
                                    <a md-ink-ripple>
                                       <span class="pull-right text-muted">
                                        <i class="fa fa-caret-down"></i>
                                       </span>
                                        <i class="glyphicon glyphicon-calendar"></i>
                                        <span class="font-normal">&nbsp; Agendamentos</span>
                                    </a>
                                    <ul class="nav nav-sub">
                                        @if(\App\Http\Helpers\Util::CheckPermissionAction('agenda_listagem','view') || in_array(\App\Http\Helpers\Util::getNivel(), array(11, 19)))
                                            <li><a md-ink-ripple href="/admin/agendas">{{Lang::get('app.agenda')}}</a>
                                            </li>
                                        @endif
                                        @if(in_array(Auth::user()->profile, array(1,3,4)) || \App\Http\Helpers\Util::CheckPermissionAction('pacientes','view'))
                                            <li><a md-ink-ripple
                                                   href="/admin/pacientes">{{Lang::get('app.pacientes')}}</a></li>
                                        @endif
                                    </ul>
                                </li>
                                @if(\App\Http\Helpers\Util::CheckPermissionAction('cirurgico_fechamento','view') || in_array(\App\Http\Helpers\Util::getNivel(), array(19)))
                                    <li>
                                        <a md-ink-ripple>
                                       <span class="pull-right text-muted">
                                        <i class="fa fa-caret-down"></i>
                                       </span>
                                            <i class="glyphicon glyphicon-calendar"></i>
                                            <span class="font-normal">&nbsp; Cirurgico</span>
                                        </a>
                                        <ul class="nav nav-sub">
                                            <li><a md-ink-ripple href="/admin/faturamento-procedimento/fechamento">Fechamento</a></li>
{{--                                            <li><a md-ink-ripple href="/admin/cirurgico/list/clear">Lista</a></li>--}}
                                        </ul>
                                    </li>
                                @endif

                                @if(\App\Http\Helpers\Util::CheckPermissionAction('relatorio','view'))
                                    <li class="nav-header m-v-sm hidden-folded green-50"> Relatórios</li>
                                    @if(in_array(Auth::user()->id, $menu_presidencial_usuarios_acessso))
                                        <li>
                                            <a md-ink-ripple>
                                                <span class="pull-right text-muted">
                                                    <i class="fa fa-caret-down"></i>
                                                </span>
                                                <i class="glyphicon glyphicon-stats"></i>
                                                <span class="font-normal">&nbsp; {{Lang::get('app.gerencia')}}</span>
                                            </a>
                                            <ul class="nav nav-sub">
                                                <li><a md-ink-ripple
                                                       href="/admin/relatorio/faturamento-lote-gerencia">{{Lang::get('app.faturamento-lote')}}</a>
                                                </li>
                                                {{--<li><a md-ink-ripple href="/admin/relatorio/previsao-faturamento">Previsão (Faturamento)</a></li>--}}
                                                {{--<li><a md-ink-ripple href="/admin/relatorio/faturamento-linha-cuidado">Faturamento <span style="font-size: 10px">(Procedimentos)</span></a></li>--}}
                                                <li><a md-ink-ripple href="/admin/relatorio/faturamento-sub-grupo">Faturamento
                                                        <span style="font-size: 10px">(SubGrupos)</span></a>
                                                </li>
                                                {{--<li><a md-ink-ripple href="/admin/relatorio/faturamento-procedimentos-medico">Faturamento <span style="font-size: 10px">(Médico)</span></a></li>--}}
                                                <li><a md-ink-ripple href="/admin/relatorio/agenda-producao">Produção
                                                        <span style="font-size: 10px">(Agenda)</span></a></li>
                                            </ul>
                                        </li>
                                    @endif
                                    @if(
                                        \App\Http\Helpers\Util::CheckPermissionAction('relatorio-analitico','view')
                                        &&
                                            (
                                                \App\Http\Helpers\Util::CheckPermissionAction('relatorio-receita-arenas','view')
                                                || \App\Http\Helpers\Util::CheckPermissionAction('relatorio-gordura-detalhado','view')
                                            )
                                        )
                                        <li>
                                            <a md-ink-ripple>
                                            <span class="pull-right text-muted">
                                                <i class="fa fa-caret-down"></i>
                                            </span>
                                                <i class="glyphicon glyphicon-stats"></i>
                                                <span class="font-normal">&nbsp; Analítico</span>
                                            </a>
                                            <ul class="nav nav-sub">
                                                @if(\App\Http\Helpers\Util::CheckPermissionAction('relatorio-receita-arenas','view'))
                                                    {{--<li><a md-ink-ripple href="/admin/relatorios/receita-arena">Receita (Arenas)</a></li>--}}
                                                @endif
                                                @if(\App\Http\Helpers\Util::CheckPermissionAction('relatorio-gordura-detalhado','view'))
                                                    <li><a md-ink-ripple href="/admin/relatorios/gordura-detalhado">Gordura
                                                            (Detalhado)</a></li>
                                                    <li><a md-ink-ripple href="/admin/relatorio/monitor-faturamento">Faturamento</a>
                                                    </li>
                                                    <li><a md-ink-ripple href="/admin/relatorio/faturamento-detalhado">Faturamento
                                                            (Detalhes)</a></li>
                                                    <li><a md-ink-ripple
                                                           href="/admin/relatorio/oferta-producao">OPPA</a></li>
                                                    <li><a md-ink-ripple href="/admin/relatorio/atendimento-condutas">Atendimento
                                                            (Condutas)</a></li>
                                                @endif
                                            </ul>
                                        </li>
                                    @endif

                                    <li>
                                        <a md-ink-ripple>
                                             <span class="pull-right text-muted">
                                                <i class="fa fa-caret-down"></i>
                                             </span>
                                            <i class="glyphicon glyphicon-stats"></i>
                                            <span class="font-normal">&nbsp; Operacional</span>
                                        </a>
                                        <ul class="nav nav-sub">
                                            @if(\App\Http\Helpers\Util::CheckPermissionAction('procedimentos','view'))
                                                <li><a md-ink-ripple
                                                       href="/admin/relatorio/procedimentos">{{Lang::get('app.procedimentos')}}</a>
                                                </li>
                                            @endif
                                            @if(\App\Http\Helpers\Util::CheckPermissionAction('producao','view'))
                                                <li><a md-ink-ripple href="/admin/relatorio/conduta">Conduta (Folha
                                                        Rosto)</a></li>
                                                <li><a md-ink-ripple href="/admin/relatorio/producao">Produção (Folha
                                                        Rosto)</a></li>
                                                <li><a md-ink-ripple href="/admin/relatorio/producao-exportacao">Produção
                                                        (02)</a></li>
                                                <li><a md-ink-ripple href="/admin/ofertas/relatorio-escala">Relatório
                                                        (Escala)</a></li>
                                                <li><a md-ink-ripple href="/admin/relatorio/remarcacao">Remarcação
                                                        (Agenda)</a></li>
                                                <li><a md-ink-ripple href="/admin/relatorio/estatistica">Estatística</a>
                                                </li>
                                                <li><a md-ink-ripple href="/admin/relatorios/pacientes-atendimento">Pacientes
                                                        (Atendimento)</a></li>
                                                <li><a md-ink-ripple href="/admin/relatorios/pacientes-faltas">Pacientes
                                                        (Faltas)</a></li>
                                            @endif
                                            @if(\App\Http\Helpers\Util::CheckPermissionAction('tempo','view'))
                                                <li><a md-ink-ripple href="/admin/relatorio/tempo">Tempo (Execução)</a>
                                                </li>
                                            @endif
                                            @if(\App\Http\Helpers\Util::CheckPermissionAction('relatorio_faturamento','view'))
                                                <li><a md-ink-ripple href="/admin/relatorio/faturamento">Faturamento
                                                        (Mensal)</a></li>
                                                <li><a md-ink-ripple href="/admin/relatorio/faturamento-gordura">Faturamento
                                                        (Gordura)</a></li>
                                                <li><a md-ink-ripple href="/admin/relatorio/atendimento-nao-faturado">Atendimentos
                                                        ñ Fat.</a></li>
                                                <li><a md-ink-ripple href="/admin/relatorio/contas-consulta">Contas
                                                        Consultas</a></li>
                                            @endif
                                            @if(\App\Http\Helpers\Util::CheckPermissionAction('relatorio_biopsia','view'))
                                                <li><a md-ink-ripple href="/admin/relatorio/biopsia">Biopsia</a></li>
                                            @endif
                                            @if(\App\Http\Helpers\Util::CheckPermissionAction('relatorio_biopsia_resumido','view'))
                                                <li><a md-ink-ripple href="/admin/relatorio/biopsia-resumo">Biopsia
                                                        Resumido</a></li>
                                            @endif

                                        </ul>

                                    @if(
                                        \App\Http\Helpers\Util::CheckPermissionAction('relatorio_pacientes_dias','view')
                                        || \App\Http\Helpers\Util::CheckPermissionAction('relatorio_indicadores_producao','view')
                                        || \App\Http\Helpers\Util::CheckPermissionAction('relatorio_aderencia_digitador','view')
                                        || \App\Http\Helpers\Util::CheckPermissionAction('relatorio_absenteismo','view')
                                        || \App\Http\Helpers\Util::CheckPermissionAction('relatorio_faturamento','view')
                                        || \App\Http\Helpers\Util::CheckPermissionAction('relatorio_tempo_recepcao','view')
                                        || \App\Http\Helpers\Util::CheckPermissionAction('oferta-agenda','view')
                                    )
                                        <li>
                                            <a md-ink-ripple>
                                             <span class="pull-right text-muted">
                                                <i class="fa fa-caret-down"></i>
                                             </span>
                                                <i class="glyphicon glyphicon-stats"></i>
                                                <span class="font-normal">&nbsp; Listagem</span>
                                            </a>
                                            <ul class="nav nav-sub">
                                                <li><a md-ink-ripple href="/admin/listagem/atendimento-pacientes">Atendimento
                                                        Pacientes</a></li>
                                                <li><a md-ink-ripple href="/admin/listagem/pacientes-importados">Pacientes
                                                        Importados</a></li>
                                            </ul>
                                        </li>
                                        <li>
                                            <a md-ink-ripple>
                                                   <span class="pull-right text-muted">
                                                      <i class="fa fa-caret-down"></i>
                                                   </span>
                                                <i class="glyphicon glyphicon-stats"></i>
                                                <span class="font-normal">&nbsp; Gerenciais</span>
                                            </a>
                                            <ul class="nav nav-sub">
                                                @if(\App\Http\Helpers\Util::CheckPermissionAction('relatorio_pacientes_dias','view'))
                                                    <li><a md-ink-ripple href="/admin/relatorio/pacientes-dias">Pacientes
                                                            (Dias)</a></li>
                                                @endif
                                                @if(\App\Http\Helpers\Util::CheckPermissionAction('relatorio_indicadores_producao','view'))
                                                    <li><a md-ink-ripple href="/admin/relatorio/indicadores-producao">Produção</a>
                                                    </li>
                                                    <li><a md-ink-ripple href="/admin/relatorio/medicos-producao">Produção
                                                            (Médicos)</a></li>
                                                @endif
                                                {{--                                                @if(\App\Http\Helpers\Util::CheckPermissionAction('relatorio_aderencia_digitador','view'))--}}
                                                {{--                                                    <li><a md-ink-ripple href="/admin/relatorio/aderencia-digitador">Aderência</a>--}}
                                                {{--                                                    </li>--}}
                                                {{--                                                @endif--}}
                                                @if(\App\Http\Helpers\Util::CheckPermissionAction('relatorio_absenteismo','view'))
                                                    <li><a md-ink-ripple
                                                           href="/admin/relatorio/absenteismo">Absenteísmo</a></li>
                                                @endif
                                                @if(\App\Http\Helpers\Util::CheckPermissionAction('relatorio_faturamento','view'))
                                                    <li><a md-ink-ripple
                                                           href="/admin/relatorio/faturista">Faturistas</a></li>
                                                @endif
                                                {{--                                                @if(\App\Http\Helpers\Util::CheckPermissionAction('relatorio_tempo_recepcao','view'))--}}
                                                {{--                                                    <li><a md-ink-ripple href="/admin/relatorio/recepcao-tempo">Tempo--}}
                                                {{--                                                            Recepção</a></li>--}}
                                                {{--                                                @endif--}}
                                                <li><a md-ink-ripple href="/admin/relatorios/importacao-agendas">Importação
                                                        (Agendas)</a></li>
                                                <li><a md-ink-ripple href="/admin/relatorios/importacao-agendas-mensal">Importação
                                                        (Agendas M.)</a></li>
                                            </ul>
                                        </li>
                                    @endif

                                    @if(
                                            \App\Http\Helpers\Util::CheckPermissionAction('relatorio-configuracoes-procedimento','view')
                                        )
                                        <li>
                                            <a md-ink-ripple>
                                                <span class="pull-right text-muted">
                                                    <i class="fa fa-caret-down"></i>
                                                </span>
                                                <i class="glyphicon glyphicon-stats"></i>
                                                <span class="font-normal">&nbsp; Configurações</span>
                                            </a>
                                            <ul class="nav nav-sub">
                                                @if(\App\Http\Helpers\Util::CheckPermissionAction('bpa','view'))
                                                    <li><a md-ink-ripple
                                                           href="/admin/relatorio/configuracoes-procedimentos">Procedimentos</a>
                                                    </li>
                                                @endif
                                            </ul>
                                        </li>
                                    @endif

                                    @if(\App\Http\Helpers\Util::CheckPermissionAction('bpa','view'))
                                        <li>
                                            <a md-ink-ripple>
                                              <span class="pull-right text-muted">
                                                 <i class="fa fa-caret-down"></i>
                                              </span>
                                                <i class="glyphicon glyphicon-download"></i>
                                                <span class="font-normal">&nbsp; Exportação</span>
                                            </a>
                                            <ul class="nav nav-sub">
                                                @if(\App\Http\Helpers\Util::CheckPermissionAction('bpa','view'))
                                                    <li><a md-ink-ripple href="/admin/relatorio/bpa">BPA</a></li>
                                                    <li><a md-ink-ripple href="/admin/relatorio/apac">APAC</a></li>
                                                @endif
                                                @if(in_array(Auth::user()->id, $menu_presidencial_usuarios_acessso))
                                                    <li><a md-ink-ripple href="/admin/relatorio/fpos">FPOs</a></li>
                                                @endif
                                            </ul>
                                        </li>
                                        @endif
                                        </li>
                                    @endif

                                    @if(
                                         \App\Http\Helpers\Util::CheckPermissionAction('faturamento','view')
                                        || \App\Http\Helpers\Util::CheckPermissionAction('cadastro','view')
                                        || \App\Http\Helpers\Util::CheckPermissionAction('manutencao','view')
                                        || \App\Http\Helpers\Util::CheckPermissionAction('importacao','view')
                                        || \App\Http\Helpers\Util::CheckPermissionAction('procedimento','view')
                                        || \App\Http\Helpers\Util::CheckPermissionAction('oferta-agenda','view')
                                        )
                                        <li class="nav-header m-v-sm hidden-folded green-50">{{Lang::get('app.configuracao')}}</li>
                                        @if(in_array(App\User::getPerfil(), array(1)))
                                            <li>
                                                <a md-ink-ripple>
                                              <span class="pull-right text-muted">
                                                 <i class="fa fa-caret-down"></i>
                                              </span>
                                                    <i class="glyphicon glyphicon-download-alt"></i>
                                                    <span class="font-normal">&nbsp; Exportação (Dados)</span>
                                                </a>
                                                <ul class="nav nav-sub">
                                                    <li><a md-ink-ripple href="/admin/exportacao/arquivos">Arquivos</a>
                                                    </li>
                                                    <li><a md-ink-ripple href="/admin/exportacao/kit-impressao">Kit de
                                                            Impressão</a></li>
                                                    <li><a md-ink-ripple href="/admin/exportacao/kit-impressao-avulso">Kit
                                                            (Avulso)</a></li>
                                                </ul>
                                            </li>
                                        @endif

                                        @if(\App\Http\Helpers\Util::CheckPermissionAction('oferta-agenda','view'))
                                            <li>
                                                <a md-ink-ripple>
                                              <span class="pull-right text-muted">
                                                 <i class="fa fa-caret-down"></i>
                                              </span>
                                                    <i class="glyphicon glyphicon-sort-by-attributes"></i>
                                                    <span class="font-normal">&nbsp; Oferta</span>
                                                </a>
                                                <ul class="nav nav-sub">
                                                    <li><a md-ink-ripple href="/admin/ofertas">Ofertar</a></li>
                                                    <li><a md-ink-ripple href="/admin/ofertas/relatorio">Relatório
                                                            (Ofertas)</a></li>
                                                    <li><a md-ink-ripple href="/admin/ofertas/importacao-excel">Importação
                                                            (Excel)</a></li>
                                                </ul>
                                            </li>
                                        @endif

                                        @if(\App\Http\Helpers\Util::CheckPermissionAction('faturamento','view'))
                                            <li>
                                                <a md-ink-ripple>
                                                   <span class="pull-right text-muted">
                                                    <i class="fa fa-caret-down"></i>
                                                   </span>
                                                    <i class="glyphicon glyphicon-export"></i>
                                                    <span class="font-normal">&nbsp; {{Lang::get('app.faturamento')}}</span>
                                                </a>
                                                <ul class="nav nav-sub">
                                                    @if(in_array(Auth::user()->id, $menu_presidencial_usuarios_acessso) || in_array(Auth::user()->id, [91]))
                                                        <li><a md-ink-ripple href="/admin/contratos">Valores
                                                                Contrato</a></li>
                                                    @endif
                                                    @if(in_array(Auth::user()->level, array(1)))
                                                        <li><a md-ink-ripple
                                                               href="/admin/faturamento">{{Lang::get('app.faturamento')}}</a>
                                                        </li>
                                                    @endif
                                                    <li><a md-ink-ripple
                                                           href="/admin/faturamento-procedimento/fechamento">Fechamento</a>
                                                    </li>
                                                    <li><a md-ink-ripple href="/admin/lotes">Contrato (Lotes)</a></li>
                                                    <li><a md-ink-ripple href="/admin/importacao/oferta">Ofertas
                                                            (Agenda)</a></li>
                                                </ul>
                                            </li>
                                        @endif
                                        @if(\App\Http\Helpers\Util::CheckPermissionAction('cadastro','view'))
                                            <li>
                                                <a md-ink-ripple>
                                                  <span class="pull-right text-muted">
                                                  <i class="fa fa-caret-down"></i>
                                                  </span>
                                                    <i class="glyphicon glyphicon-folder-open"></i>
                                                    <span class="font-normal">&nbsp; {{Lang::get('app.cadastro')}}</span>
                                                </a>
                                                <ul class="nav nav-sub">
                                                    <li><a md-ink-ripple href="/admin/cid">{{Lang::get('app.cid')}}</a>
                                                    </li>
                                                    <li><a md-ink-ripple href="/admin/guias">Guias</a></li>
                                                    <li><a md-ink-ripple href="/admin/agendamento-tipo">Tipos de
                                                            agendamento</a></li>
                                                    <li><a md-ink-ripple
                                                           href="/admin/unidades">{{Lang::get('app.unidades')}}</a></li>
                                                    <li><a md-ink-ripple
                                                           href="/admin/insumos">{{Lang::get('app.insumos')}}</a></li>
                                                    <li><a md-ink-ripple
                                                           href="/admin/arenas">{{Lang::get('app.arenas')}}</a></li>
                                                    <li><a md-ink-ripple href="/admin/arena-equipamentos">Unidades
                                                            (Equipamentos)</a></li>
                                                    <li><a md-ink-ripple
                                                           href="/admin/empresas">{{Lang::get('app.empresas')}}</a></li>
                                                    <li><a md-ink-ripple
                                                           href="/admin/pais">{{Lang::get('app.pais')}}</a></li>
                                                    <li><a md-ink-ripple
                                                           href="/admin/linha-cuidado">{{Lang::get('app.linha-cuidado')}}</a>
                                                    </li>
                                                    <li><a md-ink-ripple href="/admin/exames">Exames</a></li>
                                                    <li><a md-ink-ripple
                                                           href="/admin/profissionais">{{Lang::get('app.profissionais')}}</a>
                                                    </li>
                                                    <li><a md-ink-ripple
                                                           href="/admin/medicamentos">{{Lang::get('app.medicamentos')}}</a>
                                                    </li>
                                                    <li><a md-ink-ripple
                                                           href="/admin/estabelecimento">{{Lang::get('app.estabelecimento')}}</a>
                                                    </li>
                                                    <li><a md-ink-ripple
                                                           href="/admin/laudo-medico">{{Lang::get('app.laudo')}}</a>
                                                    </li>
                                                    <li><a md-ink-ripple href="/admin/programas">Programas</a></li>
                                                    <li><a md-ink-ripple href="/admin/tipo-atendimento">Tipo
                                                            Atendimentos</a></li>
                                                    <li><a md-ink-ripple href="/admin/condutas">Condutas</a></li>
                                                </ul>
                                            </li>
                                        @endif
                                        @if(\App\Http\Helpers\Util::CheckPermissionAction('procedimento','view'))
                                            <li>
                                                <a md-ink-ripple>
                                                  <span class="pull-right text-muted">
                                                  <i class="fa fa-caret-down"></i>
                                                  </span>
                                                    <i class="glyphicon glyphicon-tasks"></i>
                                                    <span class="font-normal">&nbsp; Procedimentos</span>
                                                </a>
                                                <ul class="nav nav-sub">
                                                    <li><a md-ink-ripple href="/admin/grupos">Grupos</a></li>
                                                    <li><a md-ink-ripple href="/admin/sub-grupos">Sub-Grupos</a></li>
                                                    <li><a md-ink-ripple href="/admin/organizacao">Organização</a></li>
                                                    <li><a md-ink-ripple href="/admin/procedimentos">Procedimentos</a>
                                                    </li>
                                                    <li><a md-ink-ripple href="/admin/procedimentos-medicos">Procedimentos
                                                            (Médicos)</a></li>
                                                    <li><a md-ink-ripple href="/admin/cbo">CBO</a></li>
                                                </ul>
                                            </li>
                                        @endif
                                        @if(\App\Http\Helpers\Util::CheckPermissionAction('manutencao','view'))
                                            <li>
                                                <a md-ink-ripple>
                                                  <span class="pull-right text-muted">
                                                    <i class="fa fa-caret-down"></i>
                                                  </span>
                                                    <i class=" mdi-action-settings i-20"></i>
                                                    <span class="font-normal">&nbsp {{Lang::get('app.manutencao')}}</span>
                                                </a>
                                                <ul class="nav nav-sub">
                                                    <li><a md-ink-ripple
                                                           href="/admin/anamnese-perguntas">{{Lang::get('app.anamnese-perguntas')}}</a>
                                                    </li>
                                                    <li><a md-ink-ripple
                                                           href="/admin/perfil">{{Lang::get('app.perfil-usuarios')}}</a>
                                                    </li>
                                                    <li><a md-ink-ripple
                                                           href="/admin/usuarios">{{Lang::get('app.usuarios')}}</a></li>
                                                </ul>
                                            </li>
                                        @endif
                                        @if(\App\Http\Helpers\Util::CheckPermissionAction('manutencao','view'))
                                            <li>
                                                <a md-ink-ripple>
                                                  <span class="pull-right text-muted">
                                                    <i class="fa fa-caret-down"></i>
                                                  </span>
                                                    <i class="fa fa-list"></i>
                                                    <span class="font-normal">&nbsp; Monitoramento</span>
                                                </a>
                                                <ul class="nav nav-sub">
                                                    <li><a md-ink-ripple href="/admin/monitoramento/crons">Rotinas
                                                            (CRON)</a></li>
                                                </ul>
                                            </li>
                                        @endif
                                        @if(\App\Http\Helpers\Util::CheckPermissionAction('importacao','view'))
                                            <li>
                                                <a md-ink-ripple>
                                                  <span class="pull-right text-muted">
                                                  <i class="fa fa-caret-down"></i>
                                                  </span>
                                                    <i class="glyphicon glyphicon-upload"></i>
                                                    <span class="font-normal">{{Lang::get('app.importacao')}}</span>
                                                </a>
                                                <ul class="nav nav-sub">
                                                    {{--<li><a md-ink-ripple href="/admin/importacao/agenda-oferta-pdf">Agenda (Oferta)</a></li>--}}
                                                    <li><a md-ink-ripple href="/admin/importacao/agenda">Agenda</a></li>
                                                    <li><a md-ink-ripple href="/admin/importacao/agenda-pdf">Agenda
                                                            (PDF)</a></li>
                                                    <li><a md-ink-ripple href="/admin/pacientes/dados-correcao">Pacientes
                                                            (Correção)</a></li>
                                                </ul>
                                            </li>
                                        @endif
                                    @endif
                                 
                                    
                                    @if(Auth::user()->email == 'rodrigo.affonso@ciesglobal.org' || 
                                       // Auth::user()->email == 'felipe.rodrigues@ciesglobal.org' || 
                                        Auth::user()->email == 'ederson.sandre@ciesglobal.org' || 
                                        Auth::user()->email == 'elias.belo@ciesglobal.org' || 
                                        Auth::user()->email == 'bruno.lima@ciesglobal.org' || 
                                        Auth::user()->email == 'gustavo.moreira@ciesglobal.org' || 
                                        Auth::user()->email == 'weverton.nascimento@ciesglobal.org' || 
                                        Auth::user()->email == 'adriele.viana@ciesglobal.org' || 
                                        Auth::user()->email == 'patricia.damico@ciesglobal.org' || 
                                        Auth::user()->email == 'leonardo.silva@ciesglobal.org' || 
                                        Auth::user()->email == 'mayra.lima@ciesglobal.org' || 
                                        Auth::user()->email == 'eduardo.danninger@ciesglobal.org' || 
                                        Auth::user()->email == 'marylaine.silva@ciesglobal.org')
                                    <li class="nav-header m-v-sm hidden-folded green-50"> Farmácia</li>

                                        <li <?=(mb_strpos($_SERVER['REQUEST_URI'], 'estoque/adicionar') !== false 
                                                || mb_strpos($_SERVER['REQUEST_URI'], 'estoque/solicitacoes') !== false
                                                ? 'class="active"' : '')?>>
                                            <a md-ink-ripple>
                                            <span class="pull-right text-muted">
                                            <i class="fa fa-caret-down"></i>
                                            </span>
                                            <i class="glyphicon glyphicon-menu-hamburger"></i>
                                            <span class="font-normal"> Estoque</span>
                                            </a>
                                            <ul class="nav nav-sub">
                                                {{--<li><a md-ink-ripple href="/admin/importacao/agenda-oferta-pdf">Agenda (Oferta)</a></li>--}}
                                                <li><a md-ink-ripple href="/admin/estoque/solicitacoes/create">Pedido medicamentos</a></li>
                                                <li><a md-ink-ripple href="/admin/estoque/adicionar">Receb. de mercadoria</a></li>
                                            </ul>
                                        </li>

                                        <li <?=(mb_strpos($_SERVER['REQUEST_URI'], 'produtos') !== false ? 'class="active"' : '')?>>
                                            <a md-ink-ripple>
                                            <span class="pull-right text-muted">
                                            <i class="fa fa-caret-down"></i>
                                            </span>
                                            <i class="glyphicon glyphicon-folder-open"></i>
                                            <span class="font-normal"> Cadastros</span>
                                            </a>
                                            <ul class="nav nav-sub">
                                                {{--<li><a md-ink-ripple href="/admin/importacao/agenda-oferta-pdf">Agenda (Oferta)</a></li>--}}
                                                <li><a md-ink-ripple href="/admin/estoque/produtos-categorias">Categoria de produtos</a></li>
                                                <li><a md-ink-ripple href="/admin/estoque/fabricantes">Fabricantes</a></li>
                                                <li><a md-ink-ripple href="/admin/estoque/fornecedores">Fornecedores</a></li>
                                                <li><a md-ink-ripple href="/admin/estoque/produtos">Produtos</a></li>
                                            </ul>
                                        </li>
                                        <li <?=(mb_strpos($_SERVER['REQUEST_URI'], 'estoque/transf') !== false 
                                                    || mb_strpos($_SERVER['REQUEST_URI'], 'estoque/receber') !== false
                                                    || mb_strpos($_SERVER['REQUEST_URI'], 'estoque/baixar') !== false
                                                ? 'class="active"' : '')?>>
                                            <a md-ink-ripple>
                                            <span class="pull-right text-muted">
                                            <i class="fa fa-caret-down"></i>
                                            </span>
                                                <i class="glyphicon glyphicon-transfer"></i>
                                                <span class="font-normal">Transferências</span>
                                            </a>
                                            <ul class="nav nav-sub">                                    
                                                <li><a md-ink-ripple href="/admin/estoque/transferir">Transferir</a></li>
                                                <li><a md-ink-ripple href="/admin/estoque/transferencias">Transferências</a></li>
                                                <li><a md-ink-ripple href="/admin/estoque/receber">Recebimento</a></li>
                                                <li><a md-ink-ripple href="/admin/estoque/baixar">Baixar</a></li>
                                               </ul>
                                        </li>
                                        <li <?=(mb_strpos($_SERVER['REQUEST_URI'], 'estoque/relatorios') !== false ? 'class="active"' : '')?>>
                                            <a md-ink-ripple>
                                            <span class="pull-right text-muted">
                                            <i class="fa fa-caret-down"></i>
                                            </span>
                                            <i class="glyphicon glyphicon-stats"></i>
                                            <span class="font-normal">Relatórios</span>
                                            </a>
                                            <ul class="nav nav-sub"></a></li>
                                                <li><a md-ink-ripple href="/admin/estoque/relatorios/entrada">Relatório de entradas</a></li>
                                                <li><a md-ink-ripple href="/admin/estoque/relatorios/baixa">Relatório de baixas</a></li>
                                                <li><a md-ink-ripple href="/admin/estoque/relatorios/transferencias">Rel. de transferências</a></li>
                                                <li><a md-ink-ripple href="/admin/estoque/relatorios/produtos-vencimentos">Rel. vencimentos</a></li>
                                            </ul>
                                        </li>
                                    @endif
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
                    <div style="height: 70px"></div>
                </div>
            </div>
        </div>
    </div>

</div>
