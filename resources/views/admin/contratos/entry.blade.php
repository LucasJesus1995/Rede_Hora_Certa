@extends('admin')

@section('content')

  <div class="card">
    <div class="card-heading">
      <h2>
        {{Lang::get('app.listagem-de-dados')}}
      </h2>
      <small></small>
    </div>
    <div class="card-tools">
    <ul class="list-inline">
        <li class="dropdown">
          <a class="md-btn md-flat md-btn-circle waves-effect" data-toggle="dropdown" md-ink-ripple="" aria-expanded="false">
            <i class="mdi-navigation-more-vert text-md"></i>
          </a>
          <ul class="dropdown-menu dropdown-menu-scale pull-right pull-up top text-color">
            <li><a href="" class="btn-back-listagem ">{{Lang::get('app.listagem')}}</a></li>
          </ul>
        </li>
      </ul>
      </div>
    <div class="card-body bg-light " id="">

       {!!Form::model($entry, array('url' => '/admin/contratos', 'class' => 'form-vertical'))!!}
       {!!Form::hidden('id')!!}
        <div class="row">
            <div class="col-md-8">

                <div class="row">
                    <div class="col-md-4">
                        {!!Form::textField('codigo', Lang::get('app.codigo'), null, array('class' => 'form-control'))!!}
                    </div>
                    <div class="col-md-8">
                        {!!Form::textField('nome', Lang::get('app.nome'), null, array('class' => 'form-control'))!!}
                    </div>
                </div>
              <div class="row">
                <div class="col-md-6">
                  {!!Form::selectField('ano_mes', App\Http\Helpers\Util::anoMes(), Lang::get('app.ano-mes'), null, array('class' => 'form-control'))!!}
                </div>
                  <div class="col-md-6">
                      {!!Form::selectField('ativo', array('1' => Lang::get('app.sim'), '0' => Lang::get('app.nao')), Lang::get('app.ativo'), "1", array('class' => 'form-control'))!!}
                  </div>
              </div>
                {!!Form::textareaField('descricao',Lang::get('app.descricao'), null, array('class'=>'no-style form-control', 'rows'=>'2'))!!}
            </div>
            <div class="col-md-4">
              {!!Form::selectMultipleField('lotes', \App\Lotes::Combo(), Lang::get('app.lote'), null, array('class'=>'no-style form-control span12','style'=>'height: 200px'))!!}
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <button class="btn-back-listagem btn btn-default waves-effect" type="button">{{Lang::get('app.cancelar')}}</button>
                    <button class="btn-submit btn btn-success waves-effect" type="button">{{Lang::get('app.salvar')}}</button>
                </div>
            </div>
        </div>
        {!!Form::close()!!}
    </div>

  </div>

@stop

@section('script')
@stop