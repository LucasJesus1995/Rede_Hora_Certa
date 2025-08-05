@extends('admin')

@section('content')

    <div class="card">
        <div class="card-heading">

            <div class="tabbable">
                <ul class="nav nav-tabs">
{{--                    <li class=""><a href="#cadastro" data-toggle="tab" class="btn-tab-cadastro">Cadastro</a></li>--}}
                    <li class="active"><a href="#pesquisa" data-toggle="tab">Pesquisa</a></li>
                </ul>
                <div class="tab-content">
{{--                    <div class="tab-pane " id="cadastro">--}}
{{--                        <div class="panel-body-default box-oferta-cadastro">--}}
{{--                            @include('admin.ofertas.registro')--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    <div class="tab-pane active" id="pesquisa">
                        <div class="panel-body-default">
                            @include('elements.layout.admin.ofertas.pesquisa')
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body bg-light lt">
            <div id="box-grid">
                <div class="text-center m-b">
                    <i class="fa fa-circle-o-notch fa-spin text-lg text-muted-lt"></i>
                </div>
            </div>
        </div>
    </div>

@stop

@section('script')
    loadingDataGridOfertas();
@stop