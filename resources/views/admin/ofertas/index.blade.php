@extends('admin')

@section('content')

    <div class="card">
        <div class="card-heading">
            <h2>
                Registro
            </h2>
            <small></small>
        </div>
        <div class="card-tools">
            <ul class="list-inline">
                <li class="dropdown">
                    <a class="md-btn md-flat md-btn-circle waves-effect" data-toggle="dropdown" md-ink-ripple="" aria-expanded="false">
                        <i class="mdi-navigation-more-vert text-md"></i>
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body bg-light " id="">
            @include('elements.layout.form-error')

            {!! Form::model(null,array('url' => '/admin/ofertas','enctype'=>'multipart/form-data')) !!}
            {!! csrf_field() !!}

            <div class="row">
                <div class="col-md-2">
                    {!!Form::textField('data', Lang::get('app.data'), null, array('class' => 'form-control date','id'=>'data'))!!}
                </div>
                <div class="col-md-4">
                    {!!Form::selectField('arena', \App\Arenas::Combo(), Lang::get('app.arenas'), null, array('class' => 'form-control combo-arena combo-arena-equipamentos chosen','id'=>'arena'))!!}
                </div>
                <div class="col-md-4">
                    {!!Form::selectField('linha_cuidado', [], Lang::get('app.linha-cuidado'), null, array('class' => 'form-control linha_cuidado combo-especialidade-profissionais chosen','id' => 'linha_cuidado'))!!}
                    {!!Form::hidden('linha_cuidado', null, ['id'=>'combo-linha_cuidado'])!!}
                </div>
                <div class="col-md-2">
                    {!!Form::selectField('equipamento', [],"Equipamento", null, array('class' => 'form-control chosen equipamento','id' => 'equipamento'))!!}
                    {!!Form::hidden('equipamento', null, ['id'=>'combo-equipamento'])!!}
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    {!! Form::fileField('file', 'Arquivo de Importação (pdf)') !!}
                </div>
                <div class="col-md-2">
                    {!!Form::selectField('tipo_atendimento', \App\Http\Helpers\Util::getTipoAtendimento(),"Tipo de Agendamento", null, array('class' => 'form-control chosen'))!!}
                </div>
                <div class="col-md-4">
                    {!!Form::selectField('medico', [],"Profissional", null, array('class' => 'form-control chosen profissionais','id' => 'medico'))!!}
                </div>
                <div class="col-md-2">
                    <div class="form-group" style="margin-top: 25px">
                        <button class="btn-submit btn btn-success waves-effect col-md-12" type="button">Importar</button>
                    </div>
                </div>
            </div>

            {!! Form::close() !!}
        </div>
        <div class="card-body bg-light lt">

            @if ($errors->any())
                <div class="alert alert-danger">
                    {!!  implode('<br />', $errors->all(':message')) !!}
                </div>
            @endif
            <div id="box-grid">
                <div class="text-center m-b">
                    <i class="fa fa-circle-o-notch fa-spin text-lg text-muted-lt"></i>
                </div>
            </div>
        </div>
    </div>

@stop

@section('script')
    {{--$(".combo-arena").change();--}}

    {{--loadingDataGridAgendaImportacaoPdf();--}}
@stop
