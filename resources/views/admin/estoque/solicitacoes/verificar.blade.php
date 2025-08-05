@extends('admin')

@section('content')

  <div class="card">
    <div class="card-heading">
      <h2>
        Solicitações - editar
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
      <h3>Solicitante: {{ $solicitante->name }} - Produto: {{ $produto->nome }} - Quantidade: {{ $solicitacao->quantidade }}</h3>
       {!!Form::model('Produtos', array('url' => route('estoque.solicitacoes.confirmar'), 'class' => 'form-vertical'))!!}
       {!!Form::hidden('id', $solicitacao->id)!!} 
       {!!Form::hidden('quantidade', $solicitacao->quantidade)!!} 
       <div class="row">
            <div class="col-md-12">
              <div class="row">  
                <div class="col-md-5">
                  {!!Form::selectField('produto_quantidade', $produtos, 'Lote-Vencimento(quantidade)', null, array('class' => 'form-control'))!!}
                </div>
                
              
              </div>
            </div>
          </div>
       
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {{-- <button class="btn-back-listagem btn btn-default waves-effect" type="button">{{Lang::get('app.cancelar')}}</button> --}}
                    <button class="btn-submit btn btn-success waves-effect" type="button">Aprovar</button>
                </div>
            </div>
        </div>
        {!!Form::close()!!}
    </div>

  </div>



@stop

@section('script')
@stop