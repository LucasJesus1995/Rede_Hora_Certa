@extends('admin')

@section('content')

    <div class="card">
        <div class="card-heading">
            <h2>Exportação de arquivo</h2>
            <small>Relatório de ofertas de agenda por periodo</small>
            <hr/>

            {!!Form::model($entry, array('url' => '/admin/ofertas/relatorio', 'class' => 'form-vertical'))!!}
            <div class="row">
                <div class="col-md-2">{!! Form::selectField('mes', \App\Http\Helpers\Util::getMes(), "Mês", date('m'), array('class' => 'form-control chosen')) !!}</div>
                <div class="col-md-3">{!! Form::selectField('unidade', \App\Arenas::Combo(), "Unidade", null, array('class' => 'form-control chosen')) !!}</div>
                <div class="col-md-3">{!! Form::selectField('linha_cuidado', \App\LinhaCuidado::Combo(), "Especialidade", null, array('class' => 'form-control chosen')) !!}</div>
                <div class="col-md-3">{!! Form::selectField('profissional', \App\Http\Helpers\Util::Medicos(), "Profissional", null, array('class' => 'form-control chosen')) !!}</div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <a class="btn btn-success col-md-12 btn-relatorio-ofertas"><i class="glyphicon glyphicon-search"></i></a>
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