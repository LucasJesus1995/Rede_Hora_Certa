<div class="card">
    <div class="card-heading">
        <h2>Listagem</h2>
        <small></small>
    </div>
    <div class="card-tools">
        @if(in_array($atendimento->status, [2,8]) && \App\Http\Helpers\UsuarioHelpers::isNivelCirurgico())
            <a href="" class="btn-new-file-atendimento btn btn-default btn-open-novo-anexo">Novo arquivo</a>
        @endif
    </div>
    <div class="card-body bg-light lt">

        <div class="box-novo-anexo well well-small" style="display: none">
            {!!Form::model($entry, array('url' => '/admin/atendimento/anexos', 'class' => 'form-vertical frm-ajax','enctype'=>'multipart/form-data'))!!}
            {!!Form::hidden('atendimento', $atendimento->id)!!}
            {!!Form::hidden('id')!!}
            <div class="row">
                <div class="col-md-3">
                    {!!Form::selectField('tipo', \App\Http\Helpers\AtendimentoHelpers::getTipoAnexoAtendimento(), "Tipo", null, array('class' => 'form-control chosen','id'=>'tipo'))!!}
                </div>
                <div class="col-md-9">
                    {!! Form::fileField('arquivo', 'Arquivo (PDF)') !!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    {!!Form::textareaField('anotacao', 'Anotação', null, array('class'=>'no-style form-control', 'rows'=>'2'))!!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-success waves-effect col-md-12" type="submit">{{Lang::get('app.salvar')}}</button>
                </div>
            </div>
            {!!Form::close()!!}
        </div>

        <div id="box-atendimento-listagem"></div>
    </div>
</div>

<script>
    function reloadFunctionPage() {
        var _agenda = [];
        _agenda.push(<?php echo $atendimento->agenda; ?>);

        getDadosComplementaresFaturamentoAgendas(_agenda);
        getAtendimentoAnexos(<?php echo $atendimento->id; ?>);
        clearInputs($(".frm-ajax"));

        $(".frm-ajax").find("input[name='atendimento']").val('<?php echo $atendimento->id; ?>');
    }

    $(document).on("click", ".btn-open-novo-anexo", function (e) {
        e.preventDefault();

        $(".box-novo-anexo").show();
        loadingMask();
    });

    getAtendimentoAnexos(<?php echo $atendimento->id; ?>);
</script>