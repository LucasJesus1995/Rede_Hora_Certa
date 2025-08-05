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

            {!! Form::model(null,array('url' => '/admin/importacao/agenda-pdf','enctype'=>'multipart/form-data')) !!}
            {!! csrf_field() !!}

            <div class="row">
                <div class="col-md-6">
                    {!!Form::selectField('oferta', \App\Ofertas::getOfertasImportacao(), "Oferta", null, array('class' => 'form-control combo-arena combo-arena-equipamentos chosen','id'=>'arena'))!!}
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
