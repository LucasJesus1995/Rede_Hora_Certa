@extends('admin')

@section('content')

  <div class="card">
    <div class="card-heading">
      <h2>
        Produtos - transferir
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

       {!!Form::model('Produtos', array('url' => route('estoque.transferir-store'), 'class' => 'form-vertical '))!!}
        <div class="row">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-4">
                  {!!Form::selectField('origem', $origem, 'Origem', null, array('class' => 'form-control chosen', 'onchange' => 'carregarTransferencias()'))!!}                
                </div>
                <div class="col-md-4">
                  {!!Form::selectField('arena', $origem, 'Destino', $arena_id, array('class' => 'form-control chosen', 'onchange' => 'carregarTransferencias()'))!!}                
                </div>
                <div class="col-md-2">
                  {!!Form::selectField('produto', \App\Produtos::ComboQuantidade(), 'Produto', null, array('class' => 'form-control chosen', 'onchange' => 'carregaLotes()'))!!}                
                </div>
                <div class="col-md-3">
                  {!!Form::selectField('lote', [], 'Lote/vencimento(quantidade em estoque)', null, array('class' => 'form-control'))!!}                
                </div>
                
                <div class="col-md-1">
                  {!!Form::textField('quantidade', 'Quantidade', null, array('class' => 'form-control', 'required'))!!}    
                </div>
                <div class="col-md-2">
                  {!!Form::textField('data', 'Data', date('d/m/Y'), array('class' => 'form-control date', 'onchange' => 'carregarTransferencias()', 'required'))!!}    
                </div>
                
              </div>
            </div>
            @if(isset($mensagem))
                {{ $mensagem }}
            @endif
          </div>
        
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {{-- <button class="btn-back-listagem btn btn-default waves-effect" type="button">{{Lang::get('app.cancelar')}}</button> --}}
                    <button class="btn-submit btn btn-success waves-effect" type="button">Transferir</button>
                </div>
            </div>
        </div>
        {!!Form::close()!!}
        
        <div id="response"></div>
    </div>

  </div>

<script>
   function carregaLotes(){  
        produto = $('#id-field-produto').val()
        origem = $('#id-field-origem').val()
        if(produto != ''){
          // console.log(arena)
          $.get( "/admin/estoque/carregar-lotes/" + produto + '/' + origem, function( data ) {
              console.log(data)
              $('#id-field-lote').find('option:not(:first)').remove()
              $.each(data, function (i, item) {
                  $('#id-field-lote').append($('<option>', { 
                      value: item.id,
                      text : item.nome + '(' + item.quantidade + ')' 
                  }));
              });
              //$('#estoqueArenas').html(data)
          });
        } 
        
    }

    function carregarTransferencias(){
        arena = $('#id-field-arena').val()
        data = $('#id-field-data').val()
        data2 = data.substring(6,10) + '-' + data.substring(3,5) + '-' + data.substring(0,2)
        if(arena != ''){
          $.get( "/admin/estoque/ver-transferencias/" + arena + '/' + data2, function( data ) {
              $( "#response" ).html( data );
          });
        }
    }

    window.onload = function() {
      carregarTransferencias();
    };

    function imprimir(){
        arena = $('#id-field-arena').val()
        data = $('#id-field-data').val()
        data2 = data.substring(6,10) + '-' + data.substring(3,5) + '-' + data.substring(0,2)
        window.open("/admin/estoque/ver-transferencias/" + arena + '/' + data2 + "/true", "Transferência")
    }

    

      
</script>

@stop

@section('script')

@stop