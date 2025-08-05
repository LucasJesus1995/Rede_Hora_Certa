@extends('admin')

@section('content')

  <div class="card">
    <div class="card-heading">
      <h2>
        Fabricantes - novo
      </h2>
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

       {!!Form::model('Produtos', array('url' => route('estoque.fabricantes.store'), 'class' => 'form-vertical'))!!}
        <div class="row">
            <div class="col-md-4">
              <div class="row">
                <div class="col-md-12">
                  {!!Form::textField('razao_social', 'Razão Social', null, array('class' => 'form-control', 'required'))!!}    
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="row">
                <div class="col-md-12">
                  {!!Form::textField('nome_fantasia', 'Nome Fantasia', null, array('class' => 'form-control', 'required'))!!}    
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="row">
                <div class="col-md-12">
                  {!!Form::textField('cnpj', 'CNPJ', null, array('class' => 'form-control', 'required'))!!}    
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="row">
                <div class="col-md-12">
                  {!!Form::textField('endereco', 'Endereço', null, array('class' => 'form-control', 'required'))!!}    
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="row">
                <div class="col-md-12">
                  {!!Form::emailField('email', 'Email', null, array('class' => 'form-control', 'required'))!!}    
                </div>
              </div>
            </div>
            <div class="col-md-2">
              <div class="row">
                <div class="col-md-12">
                  {!!Form::textField('telefone', 'Telefone', null, array('class' => 'form-control', 'required'))!!}    
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="row">
                <div class="col-md-12">
                  {!!Form::textField('cidade', 'Cidade', null, array('class' => 'form-control', 'required'))!!}    
                </div>
              </div>
            </div>
            <div class="col-md-2">
              <div class="row">
                <div class="col-md-12">
                  {!!Form::textField('cep', 'CEP', null, array('class' => 'form-control', 'maxlength' => 9))!!}    
                </div>
              </div>
            </div>
            <div class="col-md-1">
              <div class="row">
                <div class="col-md-12">
                  {!!Form::textField('uf', 'UF', null, array('class' => 'form-control',  'maxlength' => 2))!!}    
                </div>
              </div>
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
    $('#id-field-cnpj').mask('99.999.999/9999-99')
    $('#id-field-telefone').mask('(99) 9999-9999')
    $('#id-field-cep').mask('99999-999')
@stop