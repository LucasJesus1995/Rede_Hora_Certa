@extends('admin')

@section('content')

  <div class="card">
    <div class="card-heading">
      <h2>
        Fornecedores de Produtos - {{Lang::get('app.listagem-de-dados')}}
      </h2>
    </div>
       <div class="card-tools">
            <a href="{{ route('estoque.fornecedores.create') }}" class="btn btn-default">{{Lang::get('app.novo-registro')}}</a>
        </div>
    <div class="card-body bg-light lt">
        @include('elements.layout.grid-search')
        <div id="box-grid">
            
            <table class="table table-striped table-responsive table-bordered  bg-light " >
                <thead>
                    <tr role="row">
                        <th class="w-64">#</th>
                        <th>Razão social</th>
                        <th>Nome</th>
                        <th class="w-64">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($fornecedores AS $row)
                        <tr class="grid-status-{{$row->ativo}}">
                            <td>{{$row->id}}</td>
                            <td>{{$row->razao_social}}</td>
                            <td>{{$row->nome_fantasia}}</td>
                            <td nowrap>
                                {{-- <button class="btn btn-rounded btn-xs btn-success waves-effect" onclick="verEstoques({{ $row->id }})" title="Visualizar Estoques"><i class="fa fa-tasks"></i></button> --}}
                                <a href="{{ route('estoque.fornecedores.edit', $row->id) }}" title="Editar"  class="btn btn-rounded btn-xs btn-info waves-effect"><i class="fa fa-edit"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
    
           {!! $fornecedores->render() !!}
           <input type="hidden" id="url-pagination" value="{{urldecode($_SERVER['REQUEST_URI'])}}" />
           <a href="{{ route('estoque.relatorios.fornecedores') }}" class="btn btn-success">Posição Estoque Geral</a>

        </div>
    </div>
  </div>
  <div class="modal fade" id="modalEstoques" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body" id="conteudoEstoques">
          <p>One fine body&hellip;</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
  <script>
        function verEstoques(produto){  
            console.log(produto)
            $.get( "/admin/estoque/" + produto + "/ver-estoques", function( data ) {
                $('#conteudoEstoques').html(data)
                $('#modalEstoques').modal()
            });
        }
  </script>
@stop
