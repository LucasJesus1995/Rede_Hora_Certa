@extends('admin')

@section('content')

    <div class="card">
        <div class="card-heading">
            <h2>Escalas unidades</h2>
            <small>Exportação de arquivo mensal</small>
            <hr/>

            {!!Form::model($entry, array('url' => '/admin/ofertas/relatorio-escala', 'class' => 'form-vertical'))!!}
            <div class="row">
                <div class="col-md-2">{!! Form::selectField('mes', \App\Http\Helpers\Util::getMes(), "Mês", date('m'), array('class' => 'form-control chosen')) !!}</div>
                <div class="col-md-9">{!! Form::selectField('unidade', \App\Arenas::Combo(), "Unidade", null, array('class' => 'form-control chosen')) !!}</div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <a class="btn btn-success col-md-12 btn-relatorio-ofertas-escala"><i class="glyphicon glyphicon-search"></i></a>
                    </div>
                </div>
            </div>
            {!!Form::close()!!}

        </div>

        <div class="card-body bg-light lt">
            <div id="box-grid"></div>
        </div>
    </div>

@stop