<?php
if(!empty($grid->items())):
$classificacoes = \App\Http\Helpers\DataHelpers::getClassificacaoKeys();
?>

{!!Form::close()!!}

{!!Form::model($update_mass, array('url' => null, 'class' => 'form-vertical dados-em-massa'))!!}
{!!Form::hidden('data-inicial')!!}
{!!Form::hidden('data-final')!!}
{!!Form::hidden('equipamento')!!}
{!!Form::hidden('unidade')!!}
{!!Form::hidden('linha_cuidado')!!}
{!!Form::hidden('procedimento')!!}
{!!Form::hidden('intervalo')!!}
{!!Form::hidden('profissional')!!}
{!!Form::hidden('classificacao')!!}
{!!Form::hidden('status')!!}
@if(!empty($update_mass['repetir_semana']) && count($update_mass['repetir_semana']) > 0)
    {!!Form::hidden('repetir_semana', implode(",", $update_mass['repetir_semana']))!!}
@endif
{!!Form::hidden('aberta')!!}
{!!Form::hidden('horario-inicial')!!}
{!!Form::hidden('horario-final')!!}
{!!Form::close()!!}

<div class="" style="margin:  15px 0">
    <div class="accordion" id="accordion2">
        <div class="accordion-group">
{{--            <div class="accordion-heading text-right">--}}
{{--                <a class="accordion-toggle btn btn-info btn-open-atualizacao-massa" data-toggle="collapse"--}}
{{--                   data-parent="#accordion2" href="#collapseOne">Atualização em Massa</a>--}}
{{--            </div>--}}
            <div id="collapseOne" class="accordion-body collapse out">
                <div class="accordion-inner">
                    <div class="well well-small" style="margin-top: 10px;">
                        {!!Form::model($update_mass, array('url' => '/admin/ofertas/atualizacao-massa', 'class' => 'form-vertical form-atualizacao-em-massa'))!!}
                        <div class="row">
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-2">{!!Form::textField('quantidade',  'Quantidade', null, array('class' => 'form-control numeric'))!!}</div>
                                    <div class="col-md-2">{!!Form::selectField('aberta', \App\Http\Helpers\Util::Ativo(), 'Aberta', null, array('class' => 'form-control chosen'))!!}</div>
                                    <div class="col-md-4">{!!Form::textField('data_aprovacao',  'Data Aprovação', null, array('class' => 'form-control date'))!!}</div>
                                    <div class="col-md-4">{!!Form::selectField('status', \App\Http\Helpers\DataHelpers::getOfertaStatus(), 'Status', null, array('class' => 'form-control chosen'))!!}</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">{!!Form::selectField('equipamento', \App\ArenaEquipamentos::getAllComboByArena(), 'Equipamento', null, array('class' => 'form-control select-equipamento equipamento chosen ', 'id'=>'arena-equipamentos'))!!}</div>
                                    <div class="col-md-3">
                                        <div class="row">
                                            <div class="col-md-6">{!!Form::textField('horario_inicial',  'Horas', null, array('class' => 'form-control time'))!!}</div>
                                            <div class="col-md-6">{!!Form::textField('horario_final',  '&nbsp;', null, array('class' => 'form-control time'))!!}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-4">{!!Form::selectField('classificacao', \App\Http\Helpers\DataHelpers::getClassificacao(), 'Classificação', null, array('class' => 'form-control chosen'))!!}</div>
                                            <div class="col-md-8">{!!Form::textField('observacao',  'Observação', null, array('class' => 'form-control'))!!}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2" style="margin-top: 25px">
                                <button class="btn btn-success waves-effect col-md-12 submit-atualizacao-massa"
                                        type="button">Atualizar
                                </button>
                            </div>
                        </div>
                        {!!Form::close()!!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div>
    <table class="table table-striped table-responsive table-bordered  bg-light ">
        <thead>
        <tr role="row">
            <th>Código</th>
            <th>Unidade</th>
            <th>Profissional</th>
            <th>Periodo</th>
            <th>Procedimentos</th>
            <th class="w-64">{!!Lang::get('grid.acao')!!}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($grid AS $row)
            <?php
            $procedimentos = \App\Ofertas::getProcedimentos($row->id);
            ?>
            <tr class="table-text-small" id="oferta-{!! $row->id !!}">
                <td>{!! $row->codigo !!}</td>
                <td class="table-text-small" title="Codigo: {!! $row->id !!}">
                    <div class="row">
                        <div class="col-md-1">
                            @if($row->aberta == 1)
                                <span class="glyphicon glyphicon-folder-open alert-success "></span>
                            @else
                                <span class="glyphicon glyphicon-folder-close alert-danger "></span>
                            @endif
                        </div>
                        <div class="col-md-11">
                            <small>{!! $row->arena !!}</small>
                            <br/>
                            <strong>{!! $row->linha_cuidado !!}</strong>
                            <small>({!! \App\Http\Helpers\Util::clearFields($row->equipamento, 'equipamento') !!}
                                )</small><br/>
                            @if(!empty($classificacoes[$row->classificacao]))
                                <em> {!! $classificacoes[$row->classificacao] !!}</em> @endif
                        </div>
                    </div>
                </td>
                <td class="table-text-small">
                    <small>{!! $row->profissional !!}</small>
                    <br/>
                    <strong>{!! $row->quantidade !!} vagas</strong>
                    @if(!empty( \App\Http\Helpers\Util::calculaIntervaloOferta($row->hora_inicial, $row->hora_final, $row->quantidade)))
                        <strong>({!!  \App\Http\Helpers\Util::calculaIntervaloOferta($row->hora_inicial, $row->hora_final, $row->quantidade) !!}
                            )</strong>
                    @endif
                </td>
                <td class="table-text-small">
                    <small><strong>{!! \App\Http\Helpers\Util::clearFields($row->data, 'date') !!}</strong>
                        ({!! \App\Http\Helpers\Util::clearFields($row->periodo, 'periodo') !!})</small>
                    <br/>
                    {!! \App\Http\Helpers\Util::clearFields($row->hora_inicial ,'time') !!}
                    ~ {!! \App\Http\Helpers\Util::clearFields($row->hora_final, 'time') !!}
                    <div class="status-oferta btn-oferta-status cursor-point" data-id="{!! $row->id !!}">
                        <strong>@if(!empty($row->status)){!! \App\Http\Helpers\DataHelpers::getOfertaStatus($row->status) !!}@endif</strong>
                    </div>
                </td>
                <td class="table-text-small">
                    @if($procedimentos)
                        @foreach($procedimentos AS $procedimento)
                            <small title="{!! $procedimento->procedimentos->nome !!}">- {!! str_limit($procedimento->procedimentos->nome, 40) !!}</small>
                            <br/>
                        @endforeach
                    @endif
                </td>
                <td nowrap>
                    <div class="btn-group">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                            ... &nbsp;&nbsp;&nbsp;<span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
{{--                            <li><a href="#" class="btn-editar-oferta" data-id="{!! $row->id !!}">Editar</a></li>--}}
{{--                            <li><a href="#" class="btn-oferta-status" data-id="{!! $row->id !!}">Status</a></li>--}}
{{--                            <li><a href="#" class="btn-oferta-ocorrencia" data-id="{!! $row->id !!}">Ocorrência</a></li>--}}
{{--                            @if(strlen($row->data_aprovacao) == 0)--}}
{{--                                <li class="oferta-aprovacao"><a href="#" class="btn-oferta-aprovacao"--}}
{{--                                                                data-id="{!! $row->id !!}">Aprovação</a></li>--}}
{{--                            @endif--}}
{{--                            <li role="separator" class="divider"></li>--}}
                            <li><a href="#" class="btn-oferta-excluir" data-id="{!! $row->id !!}">Excluir</a></li>
                        </ul>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {!! $grid->render() !!}
    <input type="hidden" id="url-pagination" value="{{urldecode($_SERVER['REQUEST_URI'])}}"/>

{{--    @if(in_array(Auth::user()->id, [423, 1, 627]))--}}
{{--        <div class="text-right">--}}
{{--            <a href="" class="delete-oferta-massa btn btn-danger">Remover todos os registros</a>--}}
{{--        </div>--}}
{{--    @endif--}}
</div>
<?php
else:
    echo "<div class='panel bg-danger pos-rlt'>
                <span class='arrow top  b-danger '></span>
                <div class='panel-body'>" . Lang::get('grid.nenhum-registro-encontrado') . "</div>
              </div>";
endif;
?>