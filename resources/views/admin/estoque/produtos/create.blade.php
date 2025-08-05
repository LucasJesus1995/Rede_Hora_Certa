@extends('admin')

@section('content')

  <div class="card">
    <div class="card-heading">
      <h2>
        Produtos - novo
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

       {!!Form::model('Produtos', array('url' => route('produtos.store'), 'class' => 'form-vertical'))!!}
        <div class="row">
            <div class="col-md-12">
              <div class="row">  
                <div class="col-md-2">
                  {!!Form::selectField('categoria', \App\ProdutosCategorias::Combo(), 'Categoria', null, array('class' => 'form-control'))!!}
                </div>              
                <div class="col-md-2">
                  {!!Form::textField('codigo', 'Código', null, array('class' => 'form-control', 'required'))!!}    
                </div>
                <div class="col-md-8">
                  {!!Form::textField('nome', 'Nome', null, array('class' => 'form-control', 'required'))!!}    
                </div>
                <div class="col-md-2">
                  {!!Form::selectField('unidade_medida', ['unidade' => 'unidade', 'metro' => 'metro', 'grama' => 'grama'], 'Unidade de medida', null, array('class' => 'form-control'))!!}                
                </div>
                <div class="col-md-2">
                  {!!Form::selectField('tipo_apresentacao', \App\ProdutosTiposApresentacoes::Combo(), 'Tipo Apresentação', null, array('class' => 'form-control'))!!}
                </div> 
                <div class="col-md-8">
                  {!!Form::textField('descricao', 'Descrição', null, array('class' => 'form-control'))!!}
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
@stop