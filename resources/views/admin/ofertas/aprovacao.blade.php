<div class="well well-sm">
    <div id="error-validation"></div>

    {!!Form::model($entry, array('url' => '/admin/ofertas/aprovacao', 'class' => 'form-vertical'))!!}
    {!!Form::hidden('oferta', $oferta->id)!!}
    <div class="row">
        <div class="col-md-4">
            {!!Form::textField('data_aprovacao',  'Data Aprovação', null, array('class' => 'form-control date'))!!}
        </div>
        <div class="col-md-8">
            {!!Form::selectField('status', \App\Http\Helpers\DataHelpers::getOfertaStatus(), 'Status', null, array('class' => 'form-control chosen'))!!}
        </div>
        <div class="col-md-12">
            <a class="btn btn-success col-md-12 btn-aprovacao-ofertas">Salvar</a>
        </div>
    </div>
    {!!Form::close()!!}

</div>
