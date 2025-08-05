@extends('admin')

@section('content')

  <div class="card">
    <div class="card-heading">
      <h2>
        Categoria de Produtos - editar
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

       {!!Form::model('Produtos', array('url' => route('produtos_categorias.update'), 'class' => 'form-vertical', 'method' => 'PUT'))!!}
       {!!Form::hidden('id', $produto_categoria->id)!!}
       {!!Form::hidden('pagina_anterior', $pagina_anterior)!!}
        <div class="row">
            <div class="col-md-12">
              <div class="row">                
                <div class="col-md-12">
                  {!!Form::textField('nome', 'Nome', $produto_categoria->nome, array('class' => 'form-control', 'required'))!!}    
                </div>
            </div>
          </div>
        
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <a href="{{ $pagina_anterior }}" class="btn btn-default waves-effect" type="button">{{Lang::get('app.cancelar')}}</a>
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