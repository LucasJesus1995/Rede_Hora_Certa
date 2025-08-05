@extends('admin')

@section('content')

  <div class="card">
    <div class="card-heading">
      <h2>
        {{Lang::get('app.Video')}}
      </h2>
      <small>{{Lang::get('Video Importação Agenda')}}</small>
    </div>
   <div class="card-tools">
        <a href="importacaoagenda.mp4" class="btn-new-entry btn btn-default">{{Lang::get('app.video aula')}}</a>
    </div>
  </div>

@stop