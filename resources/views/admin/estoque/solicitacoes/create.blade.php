@extends('admin')

@section('content')

  <div class="card">
    <div class="card-heading">
      <h2>
        Solicitações
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

       {!!Form::model('Produtos', array('url' => route('estoque.solicitacoes.store'), 'class' => 'form-vertical'))!!}
        <div class="row">
            <div class="col-md-12">
              <div class="row">  
                <div class="col-md-5">
                  {!!Form::selectField('arena', $arenas, 'Unidade', $arena_id, array('class' => 'form-control', 'onchange="atualizar()"'))!!}
                </div>
                <div class="col-md-5">
                  {!!Form::selectField('produto', $produtos, 'Produto', null, array('class' => 'form-control'))!!}
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
        <div id="box-grid">
          
          @if($solicitacoes)
            
          <table class="table table-striped table-responsive table-bordered  bg-light " >
              <thead>
                  <tr role="row">
                      <th class="w-64">#</th>
                      <th>Unidade</th>
                      <th>Produto</th>
                      <th>Quantidade</th>
                      <th>Solicitante</th>
                      <th>Data e Hora</th>
                      <th class="w-64">Ações</th>
                  </tr>
              </thead>
              <tbody>
                  @foreach($solicitacoes AS $row)
                      <tr class="grid-status-{{$row->ativo}}">
                          <td>{{$row->id}}</td>
                          <td>{{$row->arena}}</td>
                          <td>{{$row->produto}}</td>
                          <td>{{$row->quantidade}}</td>
                          <td>{{$row->solicitante}}</td>
                          <td>{{date("d/m/Y H:i", strtotime($row->created_at))}}</td>
                          <td nowrap>
                              <a href="{{ route('estoque.solicitacoes.verificar', $row->id) }}" title="Editar"  class="btn btn-rounded btn-xs btn-success waves-effect"><i class="fa fa-tasks"></i></a>
                              <a href="{{ route('estoque.solicitacoes.edit', $row->id) }}" title="Editar"  class="btn btn-rounded btn-xs btn-info waves-effect"><i class="fa fa-edit"></i></a>
                          </td>
                      </tr>
                  @endforeach
              </tbody>
          </table>
         
         {!! $solicitacoes->render() !!}
         <input type="hidden" id="url-pagination" value="{{urldecode($_SERVER['REQUEST_URI'])}}" />
         @endif
      </div>
    </div>

  </div>



@stop

@section('script')
  function atualizar(){
      window.location = '/admin/estoque/solicitacoes/create/' + $('#id-field-arena').val()
  }
@stop