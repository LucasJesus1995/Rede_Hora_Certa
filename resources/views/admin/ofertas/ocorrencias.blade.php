<div class="well well-sm">
    <div id="error-validation"></div>

    {!!Form::model($entry, array('url' => '/admin/ofertas/ocorrencias', 'class' => 'form-vertical'))!!}
    {!!Form::hidden('oferta', $oferta->id)!!}
    <div class="row">
        <div class="col-md-12">
            {!!Form::selectField('status', \App\Http\Helpers\DataHelpers::getOfertaStatus(), 'Status (Oferta)', null, array('class' => 'form-control chosen'))!!}
        </div>
        <div class="col-md-12">
            {!!Form::textareaField('descricao',"Descrição", null, array('class'=>'no-style form-control', 'rows'=>'5'))!!}
        </div>
        <div class="col-md-12">
            <a class="btn btn-success col-md-12 btn-ocorrencias-ofertas">Salvar</a>
        </div>
    </div>
    {!!Form::close()!!}

</div>
