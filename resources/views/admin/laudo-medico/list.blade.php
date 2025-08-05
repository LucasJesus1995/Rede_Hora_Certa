@extends('admin')

@section('content')

  <div class="card">
    <div class="card-heading">
      <h2>
        {{Lang::get('app.listagem-de-dados')}}
      </h2>
      <small>{{Lang::get('description.laudo-medico')}}</small>
    </div>
       <div class="card-tools">
            <a href="" class="btn-new-entry btn btn-default">{{Lang::get('app.novo-registro')}}</a>
        </div>
        <div class="card-body bg-light lt">
            {!!Form::open( array('class' => 'form-vertical','id' => 'form-pesquisa','method'=>'GET'))!!}
                <div class="row">
                    <div class="col-md-5"></div>
                    <div class="col-md-3">
                        {!!Form::selectField('medico', \App\Profissionais::ComboMedicos(), null, null, array('class' => 'form-control chosen','id'=>'medico-combo'))!!}
                    </div>
                    <div class="col-md-3">
                        {!!Form::textField('q', false, null, array('class' => 'form-control','placeholder'=>Lang::get('app.pesquisa'),'id'=>'input-search'))!!}
                    </div>
                    <div class="col-md-1">
                        <a id="btn-search-grid-laudo-medico" class="btn btn-icon btn-rounded btn-info waves-effect"><i class="fa fa-search"></i></a>
                    </div>
                </div>
            {!!Form::close()!!}
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