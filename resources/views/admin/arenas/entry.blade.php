@extends('admin')

@section('content')

  <div class="card">
    <div class="card-heading">
      <h2>
        {{Lang::get('app.listagem-de-dados')}}
      </h2>
      <small>{{Lang::get('description.arena')}}</small>
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

       {!!Form::model($entry, array('url' => '/admin/arenas', 'class' => 'form-vertical'))!!}
       {!!Form::hidden('id')!!}
        <div class="row">
            <div class="col-md-6">
              {!!Form::selectField('responsavel', \App\Http\Helpers\Util::Medicos(), Lang::get('app.responsavel'), null, array('class' => 'form-control'))!!}

                <div class="row">
                    <div class="col-md-9">{!!Form::textField('nome', Lang::get('app.nome'), null, array('class' => 'form-control'))!!}</div>
                    <div class="col-md-3">{!!Form::textField('alias', Lang::get('app.alias'), null, array('class' => 'form-control'))!!}</div>
                </div>
              <div class="row">
                <div class="col-md-9">{!!Form::textField('endereco', Lang::get('app.endereco'), null, array('class' => 'form-control'))!!}</div>
                <div class="col-md-3">{!!Form::textField('numero', Lang::get('app.numero'), null, array('class' => 'form-control'))!!}</div>
              </div>
              <div class="row">
                <div class="col-md-8">{!!Form::textField('bairro', Lang::get('app.bairro'), null, array('class' => 'form-control'))!!}</div>
                <div class="col-md-4">{!!Form::textField('estado', Lang::get('app.estado'), null, array('class' => 'form-control'))!!}</div>
              </div> 
              <div class="row">
                <div class="col-md-6">{!!Form::textField('telefone', Lang::get('app.telefone'), null, array('class' => 'form-control cell-phone'))!!}</div>
                <div class="col-md-6">{!!Form::textField('celular', Lang::get('app.celular'), null, array('class' => 'form-control cell-phone'))!!}</div>
              </div>
                {!!Form::textareaField('descricao',Lang::get('app.descricao'), null, array('class'=>'no-style form-control', 'rows'=>'8'))!!}
            </div>
            <div class="col-md-6">
                {!!Form::selectField('unidade', \App\Unidades::Combo(), Lang::get('app.unidade'), null, array('class'=>'no-style form-control'))!!}
                {!!Form::textField('cnes', Lang::get('app.cnes'), null, array('class' => 'form-control'))!!}
                {!!Form::selectField('ativo', \App\Http\Helpers\Util::Ativo(), Lang::get('app.ativo'), null, array('class' => 'form-control'))!!}
                {!!Form::selectMultipleField('linha_cuidado', \App\LinhaCuidado::Combo(), Lang::get('app.linha-cuidado'), null, array('class'=>'no-style form-control','style'=>'height: 320px'))!!}
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
              <hr />
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