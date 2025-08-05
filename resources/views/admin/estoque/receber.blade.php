@extends('admin')

@section('content')

  <div class="card">
    <div class="card-heading">
      <h2>
        Produtos - receber
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

       {!!Form::model('Produtos', array('url' => route('estoque.receber-confirma'), 'class' => 'form-vertical'))!!}
        <div class="row">
            <div class="col-md-12">
              <div class="row">                
                <div class="col-md-10">
                  {!!Form::selectField('arena', $arenas, 'Arena', null, array('class' => 'form-control chosen', 'onchange' => 'verTransferenciasReceber()'))!!}                
                </div>
                <div class="col-md-2">
                  {!!Form::textField('data', 'Data', date('d/m/Y'), array('class' => 'form-control date', 'onchange' => 'verTransferenciasReceber()', 'required'))!!}    
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
        @if($status)
          {{ $status }}
        @endif
        <div id="response"></div>

    </div>

  </div>

<script>
    function verTransferenciasReceber(){
        arena = $('#id-field-arena').val()
        data = $('#id-field-data').val()
        data2 = data.substring(6,10) + '-' + data.substring(3,5) + '-' + data.substring(0,2)
        if(arena != ''){
          $.get( "/admin/estoque/ver-transferencias-receber/" + arena + '/' + data2, function( data ) {
              $( "#response" ).html( data );
          });
        }

    }

    function confirmarRecebimento(id){
        if(window.confirm('Confirma o recebimento?')){
            $.get( "/admin/estoque/receber-store/" + id , function( data ) {
              console.log(data)
                verTransferenciasReceber();
            });
        }
    }
</script>


@stop

@section('script')
@stop