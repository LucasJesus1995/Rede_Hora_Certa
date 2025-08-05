@extends('admin')

@section('content')
  <div class="card">
    <div class="card-heading">
      <h2>
        Produtos - baixar
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
      {!!Form::model('Produtos', array('url' => route('estoque.baixar-store'), 'class' => 'form-vertical'))!!}
      
      <div class="row">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-4">
                  {!!Form::selectField('arena', $arenas, 'Arena', null, array('class' => 'form-control', 'onchange' => 'carregaProdutos()'))!!}                
                </div>
                <div class="col-md-3">
                  {!!Form::selectField('produto_quantidade', [], 'Produto / Lote / Vencimento / Quant Estoque', null, array('class' => 'form-control'))!!}                
                </div>
                <div class="col-md-2">
                  {!!Form::selectField('tipo_baixa', ['CO' => 'Consumo', 'PV' => 'Perda por validade', 'AV' => 'Avaria'], 'Motivo da baixa', null, array('class' => 'form-control'))!!}                
                </div>
                <div class="col-md-2">
                  {!!Form::selectField('tipo_consumo', ['CC' => 'Centro Cirurgico', 'AC' => 'Acolhimento', 'CP' => 'Carro de Parada', 'ED' => 'EDA/Colono'], 'Tipo de consumo', null, array('class' => 'form-control'))!!}                
                </div>
                <div class="col-md-1">
                  {!!Form::textField('quantidade', 'Quantidade', null, array('class' => 'form-control', 'required'))!!}    
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <button class="btn-submit btn btn-success waves-effect" type="button">Baixar</button>
                </div>
            </div>
        </div>
        {!!Form::close()!!}
        {{ $status }}
        <div class="row" id="estoqueArenas">
            
        </div>
    </div>

  </div>
  <script>
    function carregaProdutos(){  
        arena = $('#id-field-arena').val()
        if(arena != ''){
          // console.log(arena)
          $.get( "/admin/estoque/" + arena + "/arena-produtos", function( data ) {
              console.log(data)
              $('#id-field-produto_quantidade').find('option:not(:first)').remove()
              $.each(data, function (i, item) {
                  $('#id-field-produto_quantidade').append($('<option>', { 
                      value: item.id,
                      text : item.nome + ' / ' + item.quantidade 
                  }));
              });
              //$('#estoqueArenas').html(data)
          });
        } else {
          $('#estoqueArenas').html('')
        }
        
    }

    function carregaLotes(){  
        arena = $('#id-field-arena').val()
        if(arena != ''){
          // console.log(arena)
          $.get( "/admin/estoque/" + arena + "/arena-produtos", function( data ) {
              console.log(data)
              $('#id-field-produto').find('option:not(:first)').remove()
              $.each(data, function (i, item) {
                  $('#id-field-produto').append($('<option>', { 
                      value: item.id,
                      text : item.nome + '(' + item.quantidade + ')' 
                  }));
              });
              //$('#estoqueArenas').html(data)
          });
        } else {
          $('#estoqueArenas').html('')
        }
        
    }
    function exportarExcel(){
        arena = $('#id-field-arena').val()
        // alert(arena)
        window.location = "/admin/estoque/" + arena + "/arenas-estoque/true"

    }
</script>


@stop

@section('script')
@stop