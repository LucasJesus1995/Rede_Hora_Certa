<div id="error-validation"></div>
<div class="row">
    {!!Form::model($entry, array('url' => '/admin/ofertas', 'class' => 'form-vertical'))!!}
    {!!Form::hidden('id')!!}

    <div class="col-md-8">
        <div class="row">
            <div class="col-md-3">{!!Form::textField('data',  'Data', null, array('class' => 'form-control date'))!!}</div>
            <div class="col-md-5">{!!Form::selectField('arena', \App\Arenas::Combo(), 'Arena', null, array('class' => 'form-control combo-arena combo-arena-equipamentos chosen'))!!}</div>
            <div class="col-md-4">{!!Form::selectField('linha_cuidado', !empty($linha_cuidado) ? $linha_cuidado : [], 'Especialidade', null, array('class' => 'form-control linha_cuidado combo-especialidade-profissionais combo-especialidade-procedimentos-principais '))!!}</div>
        </div>

        <div class="row">
            <div class="col-md-3">{!!Form::selectField('equipamento', !empty($equipamentos) ? $equipamentos : [], 'Equipamento', null, array('class' => 'form-control equipamento chosen', 'id'=>'equipamento'))!!}</div>
            <div class="col-md-9">{!!Form::selectField('profissional', !empty($profissionais) ? $profissionais : [], 'Profissional', null, array('class' => 'form-control profissionais chosen'))!!}</div>
        </div>

        <div class="row">
            <div class="col-md-3">{!!Form::selectField('periodo', \App\Http\Helpers\DataHelpers::getPeriodo(), 'Periodo', null, array('class' => 'form-control chosen'))!!}</div>
            <div class="col-md-5">
                <div class="row">
                    <div class="col-md-6">{!!Form::textField('hora_inicial',  'Horario Inicial', null, array('class' => 'form-control time'))!!}</div>
                    <div class="col-md-6">{!!Form::textField('hora_final',  'Horario Final', null, array('class' => 'form-control time'))!!}</div>
                </div>
            </div>
            <div class="col-md-4">{!!Form::selectField('status', \App\Http\Helpers\DataHelpers::getOfertaStatus(), 'Status', null, array('class' => 'form-control chosen'))!!}</div>
        </div>

        <div class="row">
            <div class="col-md-3">{!!Form::textField('data_aprovacao',  'Data Aprovação', null, array('class' => 'form-control date'))!!}</div>
            <div class="col-md-3">{!!Form::selectField('natureza', \App\Http\Helpers\DataHelpers::getNatureza(), 'Natureza', null, array('class' => 'form-control chosen'))!!}</div>
            <div class="col-md-2">{!!Form::textField('quantidade',  'Quantidade', null, array('class' => 'form-control numeric'))!!}</div>
            <div class="col-md-2">{!!Form::selectField('aberta', \App\Http\Helpers\Util::Ativo(), 'Aberta', !empty($entry['aberta']) ? $entry['aberta'] : null, array('class' => 'form-control chosen'))!!}</div>
            <div class="col-md-2">{!!Form::selectField('repetir', \App\Http\Helpers\Util::Ativo(), 'Repetir (> mês)', !empty($entry['repetir']) ? $entry['repetir'] : null, array('class' => 'form-control chosen'))!!}</div>
        </div>

        <div class="row">
            <div class="col-md-4">{!!Form::selectField('classificacao', \App\Http\Helpers\DataHelpers::getClassificacao(), 'Classificação', null, array('class' => 'form-control chosen'))!!}</div>
            <div class="col-md-8">{!!Form::textField('observacao',  'Observação', null, array('class' => 'form-control'))!!}</div>
        </div>
    </div>

    <div class="col-md-4">
        {!!Form::selectField('procedimentos[]', !empty($procedimentos) ? $procedimentos : [], 'Procedimentos', null, array('class' => 'form-control procedimentos', 'multiple'=>true, 'style'=>'height: 238px; overflow-x: scroll'))!!}

        <div class="row">
            <div class="col-md-12">
                @foreach(\App\Http\Helpers\Util::diaSemanaAbreviado() AS $k => $label)

                    <div class="pull-left" style="width: 14.24%">
                        <fieldset class="form_fieldset">
                            <input type="checkbox" value="{!! $k !!}" id="repetir-semana-{!! $k !!}" name="repetir_semana[]" class="repetir-semana">
                            <label for="repetir-semana-{!! $k !!}">{!! $label !!}</label>
                        </fieldset>
                    </div>

                @endforeach
            </div>
        </div>

        <div class="form-group align-right" style="margin-top: 16px">
            <div class="row">
                <div class="col-md-8">
                    <button class="btn btn-success waves-effect col-md-12 submit-ofertas" type="button">Salvar</button>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-danger waves-effect col-md-12 cancelar-ofertas" type="button">Cancelar</button>
                </div>
            </div>

        </div>
    </div>

    {!!Form::close()!!}
</div>
