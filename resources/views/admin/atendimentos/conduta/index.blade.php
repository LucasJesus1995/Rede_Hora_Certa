<?php
$disabled = null;
//if (!\App\Http\Helpers\UsuarioHelpers::isNivelCirurgico()) {
//    $disabled = true;
//}
//
//if (in_array($atendimento->status, [6, 98, 99])) {
//    $disabled = true;
//}
?>

<div class="card">
    <div class="card-tools"></div>
    <div class="card-body bg-light lt">

        <div class="well well-small">
            {!!Form::model($entry, array('url' => '/admin/atendimento/conduta', 'class' => 'form-vertical frm-ajax'))!!}
            {!!Form::hidden('atendimento', $atendimento->id)!!}
            {!!Form::hidden('id')!!}
            <div class="row">
                <div class="col-md-12">
                    {!! Form::selectField('tipo_atendimento', $tipos_atendimento , "Tipo de Atendimento", null, array('class' => 'form-control chosen conduta_tipo_atendimentos', 'disabled' => $disabled )) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    {!! Form::selectField('conduta', $condutas, "Conduta Principal", null, array('class' => 'form-control chosen combo_condutas', 'disabled' => $disabled)) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    {!! Form::selectField('conduta_secundaria', $condutas, "Conduta Secundária", null, array('class' => 'form-control chosen combo_condutas', 'disabled' => $disabled)) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    {!! Form::selectField('conduta_regulacao', $condutas_regulacao, "Regulação", null, array('class' => 'form-control chosen combo_conduta_regulacao', 'disabled' => $disabled)) !!}
                </div>
                <div class="col-md-6">
                    {!! Form::selectField('conduta_opcao', $lateralidades, "Lateralidade", null, array('class' => 'form-control chosen', 'disabled' => $disabled)) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    {!!Form::textareaField('conduta_descricao', 'Descrição', null, array('class'=>'no-style form-control', 'rows'=>'2', 'disabled' => $disabled))!!}
                </div>
            </div>
{{--            @if(!in_array($atendimento->status, [6, 98, 99]) && \App\Http\Helpers\UsuarioHelpers::isNivelCirurgico())--}}
                <div class="row">
                    <div class="col-md-12">
                        <button class="btn-submit btn btn-success waves-effect col-md-12" type="button">{{Lang::get('app.salvar')}}</button>
                    </div>
                </div>
{{--            @endif--}}
            {!!Form::close()!!}
        </div>
    </div>
</div>

<script>
    function reloadFunctionPage() {
        var _agenda = [];
        _agenda.push(<?php echo $atendimento->agenda; ?>);

        getDadosComplementaresFaturamentoAgendas(_agenda);
        closeModal();
    }

    loadingMask();
</script>