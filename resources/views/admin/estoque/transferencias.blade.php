@extends('admin')

@section('content')

  <div class="card">
    <div class="card-heading">
      <h2>
        Transferências - {{Lang::get('app.listagem-de-dados')}}
      </h2>
    </div>
       <div class="card-tools">
            {{-- <a href="{{ route('transferencias.create') }}" class="btn btn-default">{{Lang::get('app.novo-registro')}}</a> --}}
        </div>
    <div class="card-body bg-light lt">
        {{-- @include('elements.layout.grid-search') --}}
        <div id="box-grid">
            
            <table class="table table-striped table-responsive table-bordered  bg-light " >
                <thead>
                    <tr role="row">
                        <th class="w-64">#</th>
                        <th>Produto</th>
                        <th>Quantidade</th>
                        <th>Origem</th>
                        <th>Destino</th>
                        <th>Data Transferência</th>
                        {{-- <th class="w-64">Ações</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach($transferencias AS $row)
                        <tr class="grid-status-{{$row->ativo}}">
                            <td>{{$row->id}}</td>
                            <td>{{$row->produto}}</td>
                            <td>{{$row->quantidade}}</td>
                            <td>{{ !empty($row->nome_origem) ? $row->nome_origem : 'CD'}}</td>
                            <td>{{!empty($row->arena) ? $row->arena : 'CD'}}</td>
                            <td>{{ date('d/m/Y H:i', strtotime($row->created_at)) }}</td>
                            {{-- <td nowrap>
                                <button class="btn btn-rounded btn-xs btn-info waves-effect" onclick="verEstoques({{ $row->id }})" title="Estoques"><i class="fa fa-edit"></i></button>
                                <a href="{{ route('transferencias.edit', $row->id) }}"  class="btn btn-rounded btn-xs btn-info waves-effect"><i class="fa fa-edit"></i></a>
                            </td> --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
    
           {!! $transferencias->render() !!}
           <input type="hidden" id="url-pagination" value="{{urldecode($_SERVER['REQUEST_URI'])}}" />
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
