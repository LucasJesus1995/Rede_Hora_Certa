@extends('admin')

@section('content')

    {!! Charts::assets() !!}

@include('graficos.lotes')

    <div class="row">
        <div class="col-md-6">
            {!! $chart->render() !!}
        </div>
    </div>


@stop

@section('script')
@stop