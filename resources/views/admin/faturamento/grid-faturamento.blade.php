<?php
if(empty($error)):
$util = new \App\Http\Helpers\Util();
?>

<hr/>
<div id="box-atualizacao-massa" class="panel panel-default margin10 ">
    <div class="panel-heading">
        Atualização em massa<br>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-4">
                {!!Form::textField('filter', "Pesquisa (Nome ou CNS Paciente)", null, array('class' => 'form-control','id'=>'filter'))!!}
            </div>
            @if(!empty($params['procedimento']))
                <div class="col-md-4">
                    @else
                        <div class="col-md-6">
                            @endif
                            {!!Form::selectField('medico', \App\Profissionais::ComboMedicos(), Lang::get('app.medico'), null, array('class' => 'chosen medico','id' => 'medico'))!!}
                        </div>
                        @if(!empty($params['procedimento']))
                            <div class="col-md-2">
                                {!!Form::textField('quantidade', Lang::get('app.quantidade'), null, array('class' => 'form-control numbers','id'=>'quantidade','maxlength'=>5))!!}
                            </div>
                        @endif
                        <div class="col-md-2 align-center">
                            <label class="" style="display: block; margin-top: -4px">&nbsp;</label>
                            <button id="btn-atualizacao-atendimento" class="btn m-v-xs btn-success waves-effect col-md-12">Atualizar <b class="badge m-l-xs">0</b></button>
                        </div>
                </div>

                @if(!empty($params['procedimento']))
                    <div class="alert alert-info">{!! \App\Http\Helpers\Util::getUserName() !!}, você está filtrando um procedimento consolidado, sendo assim é possivel informar uma quantidade e o
                        sistema irá fatura automaticamente do mais velho ao mais novo
                    </div>
                @endif
        </div>

        <table class="table table-striped table-responsive table-bordered  bg-light " id="table-atendimento-medico-faturamento">
            <thead>
            <tr role="row" class="blue-grey-100">
                <th>{!!Lang::get('app.agenda')!!}</th>
                <th>{!!Lang::get('app.paciente')!!}</th>
                <th>{!!Lang::get('app.profissional')!!} / {!!Lang::get('app.arena')!!}</th>
                <th>{!!Lang::get('app.procedimentos')!!}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($grid AS $row)
                <tr class="grid-agenda-{{$row->status}}" id="{{$row->id}}">
                    <td nowrap class="align-center">

                        <label>
                            @if(empty($params['procedimento']))
                                <?php $display = in_array($row->status, [6, 98,99]) ? " display-none " : null; ?>
                                <?php
                                if (\App\Http\Helpers\UsuarioHelpers::isNivelCirurgico()) {
                                    $display = in_array($row->status, [6, 8, 98,99]) ? " display-none " : null;
                                }
                                ?>
                                <span class="checked-atendimento {!! $display !!}">
                                    <input name="agenda[]" id="agenda-checked-{!! $row->id !!}" value="{!! $row->id !!}" class="agenda-checked" type="checkbox">
                                </span>
                            @endif

                            <span class="badge badge-sm bg-info box-codigo-atendimento" style="">{{$row->id}}</span><br/>
                            <span class="box-status" style="font-size: 11px; margin-top: -1px; display: block">{{\App\Http\Helpers\Util::StatusAgenda($row->status)}}</span><br/>
                            <span style="font-size: 9px; margin-top: -20px; display: block">{{\App\Http\Helpers\Util::DBTimestamp2User($row->data)}}</span>
                        </label>

                        <div class="box-reset">
                            @if((!\App\Http\Helpers\UsuarioHelpers::isNivelCirurgico() && in_array($row->status, [6, 8, 10, 98,99])) || (\App\Http\Helpers\UsuarioHelpers::isNivelCirurgico() && !in_array($row->status, [6, 98,99])))
                                <button md-ink-ripple="" class="md-btn md-flat m-b btn-fw text-danger waves-effect btn-reset-atendimento-faturado">
                                    <i class="fa fa-trash-o" aria-hidden="true"></i> Cancelar
                                </button>
                            @endif
                        </div>
                    </td>
                    <td class="box-paciente">
                        <strong>{{ $row->paciente_nome}}</strong>
                        @if(!empty($row->paciente_cns))
                            <span class='text-muted block text-xs'><strong>CNS:</strong> {{ $row->paciente_cns }}</span>
                        @endif
                        <hr style="margin: 5px 10px" />
                        <div class="row">
                            <div class="col-md-6">
                                <a href="" class="btn btn-default btn-xs col-md-12 btn-agenda-anexos-fechamento" agenda="{{$row->id}}">
                                    Arquivos
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="" class="btn btn-default btn-xs col-md-12 btn-agenda-conduta-fechamento" agenda="{{$row->id}}">
                                    Conduta
                                </a>
                            </div>
                        </div>
                    </td>
                    <td nowrap>
                        <div class="box-medico">

                        </div>
                        <div class="box-arena">
                            @if(!empty($row->arenas_nome))
                                <strong>{{$row->arenas_nome}}</strong><br/>
                            @endif
                            @if(!empty($row->linha_cuidado_nome))
                                ({{$row->linha_cuidado_nome}})
                            @endif
                        </div>
                    </td>
                    <td nowrap style="font-size: 11px" class="box-procedimentos">
                        <div class="box-procedimento"></div>

                        <div class="box-procedimento-button">
                            @if((!\App\Http\Helpers\UsuarioHelpers::isNivelCirurgico() && !in_array($row->status, [6, 98, 99])) || (\App\Http\Helpers\UsuarioHelpers::isNivelCirurgico() && !in_array($row->status, [6, 8,98,99])))
                                <div class="align-right"><br/>
                                    <button class="btn btn-xs btn-success waves-effect btn-atendimento-medico-procedimento" data-id="{{$row->id}}">
                                        PROCEDIMENTOS
                                    </button>
                                </div>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <br />

    <input type="hidden" id="url-pagination" value="{{urldecode($_SERVER['REQUEST_URI'])}}"/>
    <?php
    else:
        echo "<div class='panel bg-danger pos-rlt'>
                <span class='arrow top  b-danger '></span>
                <div class='panel-body'>{$error}</div>
              </div>";
    endif;
    ?>

    <script type="text/javascript">
        $("#info-agenda").hide();
        $("#btn-print-agenda").hide();
        $(".btn-new-entry").hide();
        $(".chosen").chosen({width: '100%', search_contains: true});

        $(document).on("keyup", "#filter", function (e) {
            var nth = "#table-atendimento-medico-faturamento tbody tr td:nth-child(2)";
            var valor = $(this).val().toUpperCase();

            $("#table-atendimento-medico-faturamento tbody tr").show();

            $(nth).each(function () {
                if ($(this).text().toUpperCase().indexOf(valor) < 0) {
                    $(this).parent().hide();
                }
            });
        });

        //    $("#filter").blur(function(){
        //        $(this).val("");
        //    });

        loadingMask();

        <?php if(empty($error)){?>
        atualizaDataComplementar();
        <?php }?>
    </script>