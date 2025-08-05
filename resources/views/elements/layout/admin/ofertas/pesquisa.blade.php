<div id="error-validation"></div>

{!!Form::model($entry, array('url' => '/admin/ofertas/grid', 'class' => 'form-vertical oferta-pesquisa'))!!}
<div class="row">
    <div class="col-md-3">
        <div class="row">
            <div class="col-md-6">{!!Form::textField('data-inicial',  'Intervalo (Data)',  date('d/m/Y'), array('class' => 'form-control date data-inicial'))!!}</div>
            <div class="col-md-6">{!!Form::textField('data-final',  '&nbsp;',  date('d/m/Y'), array('class' => 'form-control date data-final'))!!}</div>
        </div>
    </div>

    <div class="col-md-3">{!! Form::selectField('unidade', \App\Arenas::Combo(), "Unidade", null, array('class' => 'form-control combo-arena combo-arena-equipamentos chosen')) !!}</div>
    <div class="col-md-3">{!! Form::selectField('linha_cuidado', [], "Especialidade", null, array('class' => 'form-control linha_cuidado combo-especialidade-profissionais combo-especialidade-procedimentos-principais chosen')) !!}</div>
    <div class="col-md-3">{!!Form::selectField('equipamento', [], 'Equipamento', null, array('class' => 'form-control select-equipamento equipamento chosen ', 'id'=>'arena-equipamentos'))!!}</div>

</div>
<div class="row">
    <div class="col-md-3">{!!Form::selectField('profissional', [], 'Profissional', null, array('class' => 'form-control profissionais chosen', 'id'=>'profissionais'))!!}</div>
    <div class="col-md-3">{!!Form::selectField('status', \App\Http\Helpers\DataHelpers::getOfertaStatus(), 'Status', null, array('class' => 'form-control chosen'))!!}</div>
    <div class="col-md-1">{!!Form::selectField('aberta', \App\Http\Helpers\Util::Ativo(), 'Aberta', 0, array('class' => 'form-control chosen'))!!}</div>
    <div class="col-md-2">
        <div class="row">
            <div class="col-md-6">{!!Form::textField('horario-inicial',  'Horas', null, array('class' => 'form-control time'))!!}</div>
            <div class="col-md-6">{!!Form::textField('horario-final',  '&nbsp;', null, array('class' => 'form-control time'))!!}</div>
        </div>
    </div>

    <div class="col-md-3">
        {!!Form::selectField('classificacao', \App\Http\Helpers\DataHelpers::getClassificacao(), 'Classificação', null, array('class' => 'form-control chosen'))!!}
    </div>
</div>


<div class="row">
    <div class="col-md-3">
        {!!Form::selectField('procedimento', [],  "Procedimentos", null, array('class' => 'form-control chosen procedimentos'))!!}
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Dias da semana</label>
            <div style="margin-top: 2px">
            @foreach(\App\Http\Helpers\Util::diaSemanaAbreviado() AS $k => $label)
                <div class="pull-left" style="width: 14.24%">
                    <fieldset class="form_fieldset">
                        <input type="checkbox" value="{!! $k !!}" id="repetir-semana-{!! $k !!}" name="repetir_semana[]" class="repetir-semana">
                        <label for="repetir-semana-{!! $k !!}" style="font-size: 80%">{!! $label !!}</label>
                    </fieldset>
                </div>
            @endforeach
            </div>
        </div>
    </div>
    <div class="col-md-1">
        {!!Form::textField('intervalo',  'Intervalo', null, array('class' => 'form-control time'))!!}
    </div>
    <div class="col-md-1"></div>
    <div class="col-md-3">
        <div class="form-group">
            <label>&nbsp;</label>
            <a class="btn btn-success col-md-12 btn-pesquisa-ofertas"><i class="glyphicon glyphicon-search"></i></a>
        </div>
    </div>
</div>

