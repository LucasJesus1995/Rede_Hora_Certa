<?php
$perm_agenda_check_list = (\App\Http\Helpers\Util::CheckPermissionAction('agenda_check_list', 'view'));
$perm_agenda_atendimento = (\App\Http\Helpers\Util::CheckPermissionAction('agenda_atendimento', 'view') || \App\Http\Helpers\Util::getNivel() == 10);
$perm_agenda = (\App\Http\Helpers\Util::CheckPermissionAction('agendas', 'created'));

$linha_cuidado_kit_subespecialidade = \App\Http\Helpers\Cirurgico\KitImpressaoHelpers::getSubEspecialidadesLinhaCuidado();

$tipo_atendimento = \App\Http\Helpers\Util::getTipoAtendimento();
?>
@if(!empty($chart_score))
    <div class="m-b btn-groups margin15 align-center">
        @foreach($chart_score AS $row)
            <button class="btn m-v-xs btn-default waves-effect">{!! $row['label'] !!} <b class="badge bg-info m-l-xs">{!! $row['total'] !!}</b></button>
        @endforeach
    </div>
@endif

@if(empty($error))
    <table class="table table-striped table-responsive table-bordered  bg-light" id="table-agenda-atendimento">
        <thead>
        <tr role="row">
            <th colspan="2">{!!Lang::get('app.agenda')!!}</th>
            <th>{!!Lang::get('app.paciente')!!}</th>
            <th>{!!Lang::get('app.arena')!!}</th>
            <th>{!!Lang::get('app.status')!!}</th>
            <th class="w-64">{!!Lang::get('grid.acao')!!}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($grid AS $row)
            <tr class="grid-agenda-{{$row->status}}" id="{!! $row->id !!}">
                <td nowrap class="align-center">
                    <a href="javascript: void(0);" rel="{{$row->id}}" class="btn-gerar-atendimento no-lower">
                                <span class="badge badge-sm bg-info" style="padding: 2px 9px">
                                    {{\App\Http\Helpers\Util::DBTimestamp2UserTime2($row->data)}}
                                </span>
                        <br/>
                        <small>
                            @if(!is_null($row->import))
                                <strong>{!! $row->id !!}</strong>
                            @else
                                {!! $row->id !!}
                            @endif
                        </small>
                    </a>
                </td>
                <td nowrap>
                    {{\App\Http\Helpers\Util::DBTimestamp2UserDate2($row->data)}}<br/>
                    @if(array_key_exists($row->tipo_atendimento, $tipo_atendimento)) <strong><small
                                style="font-size: 9.5px !important;">{!! $tipo_atendimento[$row->tipo_atendimento] !!}</small></strong> @endif
                </td>
                <td>
                    <strong>{!! \App\Pacientes::nomeSocialLayout($row->paciente_nome, $row->paciente_nome_social) !!}</strong>
                    @if(!empty($row->paciente_cns))
                        <span class='text-muted block text-xs'><strong>CNS:</strong> {{ $row->paciente_cns }}</span>
                    @endif

                    @if($row->paciente_cpf)
                        <span class='text-muted block text-xs'><strong>CPF:</strong> {{ \App\Http\Helpers\Mask::Cpf($row->paciente_cpf) }}</span>
                    @endif
                </td>
                <td>
                    <strong>{{$row->arenas_nome}}</strong>
                    <br/>{{$row->linha_cuidado_nome}}
                </td>
                <td>
                    <strong class="box-agenda-status"> {{\App\Http\Helpers\Util::StatusAgenda($row->status)}} </strong>
                    <div>
                        <span class="box-biopsia"></span>
                        <span class="box-atendimento-etapa"></span>
                    </div>
                </td>
                <td nowrap>
                    @if(!in_array($row->status, [3]))
                        <div class="btn-group dropdown agenda-menu">
                            <button data-toggle="dropdown" class="btn btn-default waves-effect no-lower" type="button">
                                {!!Lang::get('app.acoes')!!} <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu animated fadeIn">
                                @if(!in_array($row->status, [0]))
                                    @if($perm_agenda_check_list && in_array($row->status, [1,2]))
                                        <li><a href="javascript: void(0);" rel="{{$row->id}}" class="btn-check-list no-lower">Recepção</a></li>
                                    @endif
                                    @if($perm_agenda_atendimento && !in_array($row->status, [7]))
                                        <li><a href="javascript: void(0);" rel="{{$row->id}}" class="btn-gerar-atendimento no-lower">{!!Lang::get('app.atendimento')!!}</a></li>
                                    @endif
                                    @if($perm_agenda)
                                        <li class="divider"></li>
                                        @if(in_array($row->linha_cuidado, [19]))
                                            <li>
                                                <a href="javascript: void(0);">APAC</a>
                                                <ul class="animated fadeIn submenu">
                                                    <li><a href="/admin/agendas/apac-impressao/{{$row->id}}-1" target="_blank" class="no-lower">&#8227; Catarata</a></li>
                                                </ul>
                                            </li>
                                        @endif
                                        <li>
                                            @if(!in_array($row->linha_cuidado, $linha_cuidado_kit_subespecialidade))
                                                <a href="/admin/agendas/kit-impressao/{{$row->id}}" target="_blank" class="no-lower">Kit Impressão</a>
                                            @else
                                                <a href="javascript: void(0);" target="_blank" class="no-lower">Kit Impressão</a>
                                                <ul class="animated fadeIn submenu">
                                                    @foreach(\App\Http\Helpers\Cirurgico\KitImpressaoHelpers::getSubEspecialidades($row->linha_cuidado) AS $k_sub_especialidade => $sub_especialidade)
                                                        <li><a href="/admin/agendas/kit-impressao/{{$row->id}}-{!! $k_sub_especialidade !!}" target="_blank"
                                                               class="no-lower">&#8227; {!! $sub_especialidade !!}</a></li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                        @if(in_array($row->status, [1,2]))
                                            <li><a href="/admin/agendas/entry/{{$row->id}}" class="no-lower">{!!Lang::get('app.editar')!!}</a></li>
                                        @endif
                                        @if(in_array($row->status, [2,3,4,10,7]))
                                            <li><a href="javascript: void(0);" data-id="{{$row->id}}" class="no-lower btn-agenda-cancelar">{!!Lang::get('app.cancelar')!!}</a></li>
                                        @endif
                                        @if(in_array($row->status, array('1')))
                                            <li><a href="javascript: void(0);" class="no-lower" id="btn-falta-agendamento" data-id="{{$row->id}}">{!!Lang::get('app.falta')!!}</a></li>
                                        @endif
                                        @if(in_array($row->status, [1,2,7]))
                                            <li><a href="javascript: void(0);" data-id="{{$row->id}}" class="btn-agendamento-remarcar no-lower">{!!Lang::get('app.remarcar')!!}</a></li>
                                        @endif
                                    @endif
                                @else
                                    <li><a href="javascript: void(0);" data-id="{{$row->id}}" class="btn-agendamento-descancelar no-lower">Reabrir</a></li>
                                @endif
                            </ul>
                        </div>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {!! $grid->render() !!}
    <input type="hidden" id="url-pagination" value="{{urldecode($_SERVER['REQUEST_URI'])}}"/>
@else
    <div class='panel bg-danger'>
        <div class='panel-body'>{!! $error !!}</div>
    </div>
@endif


<script type="text/javascript">
    <?php if(empty($error)){?>
    atualizaDataComplementarAgenda();
    <?php }?>
</script>


<style type="text/css">
    .agenda-menu, .submenu {
        list-style: none;
        padding-left: 20px;
        font-weight: bold;
    }

    .agenda-menu li:hover .submenu {
        display: block;
        max-height: 200px;
    }

    .submenu {
        overflow: hidden;
        max-height: 0;
        -webkit-transition: all 0.5s ease-out;
    }

    .submenu li:hover {
        color: #CCC;
    }
</style>