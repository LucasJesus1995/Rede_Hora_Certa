@extends('admin')

@section('content')

  <div class="card">
    <div class="card-heading">
      <h2>
        Estoque - receber
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
      @if($operacao)
      <h3>Produto: {{ $operacao->produto }} - quantidade: {{ $operacao->quantidade }}</h3>
      <h3>Arena a receber: {{ $operacao->arena }}</h3>

       {!!Form::model('Produtos', array('url' => route('estoque.receber-store'), 'class' => 'form-vertical'))!!}
        <div class="row">
            <div class="col-md-12">
              <div class="row">                
                <div class="col-md-2">
                  {!!Form::hidden('uuid', $operacao->uuid)!!}
                </div>
              </div>
            </div>
          </div>
        
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <a href="route('estoque.receber')" class="btn btn-default waves-effect" type="button">Voltar</a>
                    <button class="btn-submit btn btn-success waves-effect" type="button">Confirmar recebimento</button>
                </div>
            </div>
        </div>
        {!!Form::close()!!}
        @else 
          Código inválido ou já recebido
          <div class="form-group">
            <a href="{{route('estoque.receber')}}" class="btn btn-default waves-effect" type="button">Voltar</a>
        </div>
        @endif
        
    </div>

  </div>



@stop

@section('script')
@stop