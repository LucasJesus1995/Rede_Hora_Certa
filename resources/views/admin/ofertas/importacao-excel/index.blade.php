@extends('admin')

@section('content')

    <div class="card">
        <div class="card-heading">
            <h2>Importação de arquivo</h2>
            <small>Importação de ofertas com base no arquivo de planejamento</small>
            <hr/>

            @include('elements.layout.form-error')
            {!!Form::model(null, array('url' => '/admin/ofertas/importacao-excel', 'enctype'=>'multipart/form-data'))!!}
            {!! csrf_field() !!}
            <div class="row">
                <div class="col-md-10">{!! Form::fileField('file', 'Arquivo de Importação (xlsx)') !!}</div>
                <div class="col-md-2">
                    <div class="form-group" style="margin-top: 25px">
                        <button class="btn-submit btn btn-success waves-effect col-md-12" type="button">Importar
                        </button>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}

        </div>

        <div class="card-body bg-light lt">
            <div id="box-grid"></div>
        </div>
    </div>

@stop