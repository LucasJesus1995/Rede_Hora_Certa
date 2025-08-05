@extends('admin')

@section('content')

  <div class="card">
    <div class="card-heading">
      <h2>
        Recebimento de mercadoria
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

       {!!Form::model('Produtos', array('url' => route('estoque.store'), 'class' => 'form-vertical', 'autocomplete' => 'off'))!!}
        <div class="row">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-4">
                  {!!Form::selectField('produto', \App\Produtos::Combo(), 'Produto', null, array('class' => 'form-control'))!!}                
                </div>
                <div class="col-md-4">
                  {!!Form::selectField('fornecedor', \App\ProdutosFornecedores::Combo(), 'Fornecedor', null, array('class' => 'form-control'))!!}                
                </div>
                <div class="col-md-4">
                  {!!Form::selectField('fabricante', \App\ProdutosFabricantes::Combo(), 'Fabricante', null, array('class' => 'form-control'))!!}                
                </div>
                <div class="col-md-3">
                  {!!Form::textField('nf', 'Nota Fiscal', null, array('class' => 'form-control', 'required'))!!}    
                </div>
                <div class="col-md-3">
                  {!!Form::textField('codigo', 'Lote', null, array('class' => 'form-control', 'required'))!!}    
                </div>
                <div class="col-md-3">
                  {!!Form::textField('vencimento', 'Vencimento', null, array('class' => 'form-control date', 'required'))!!}    
                </div>
                <div class="col-md-2">
                  {!!Form::textField('quantidade', 'Quantidade', null, array('class' => 'form-control', 'required'))!!}    
                </div>
              </div>
            </div>
          </div>
        
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {{-- <button class="btn-back-listagem btn btn-default waves-effect" type="button">{{Lang::get('app.cancelar')}}</button> --}}
                    <button class="btn-submit btn btn-success waves-effect" type="button">Adicionar</button>
                </div>
            </div>
        </div>
        {!!Form::close()!!}
        {{ $msg }}
    </div>

  </div>



@stop

@section('script')
@stop