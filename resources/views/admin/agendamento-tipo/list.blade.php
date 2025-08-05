@extends('admin')

@section('content')

  <div class="card">
    <div class="card-heading">
      <h2>
        {{Lang::get('app.listagem-de-dados')}}
      </h2>
      <small>{{Lang::get('description.procedimento')}}</small>
    </div>
   <div class="card-tools">
        <a href="" class="btn-new-entry btn btn-default">{{Lang::get('app.novo-registro')}}</a>
    </div>
    <div class="card-body bg-light lt">
        @include('elements.layout.grid-search')
        <div id="box-grid">
            <div class="text-center m-b">
              <i class="fa fa-circle-o-notch fa-spin text-lg text-muted-lt"></i>
            </div>
        </div>
    </div>
  </div>

@stop

@section('script')
loadingDataGrid();
@stop