@extends('admin')

@section('content')

  <div class="card">
    <div class="card-heading">
      <h2>
        Relatório - transferências de produtos
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

       {!!Form::model('Produtos', array('url' => route('estoque.relatorios.transferencias_excel'), 'class' => 'form-vertical', 'autocomplete' => 'off'))!!}
        <div class="row">
            <div class="col-md-12">
              <div class="row">  
                <div class="col-md-4">
                  {!!Form::selectField('arena', $arenas, 'Arena', null, array('class' => 'form-control chosen', 'onchange' => 'carregarTransferencias()'))!!}                
                </div>               
                <div class="col-md-4">
                  {!!Form::textField('data_inicial', 'Data inicial', date('01/m/Y'), array('class' => 'form-control date', 'required'))!!}    
                </div>
                <div class="col-md-4">
                  {!!Form::textField('data_final', 'Data final', date('t/m/Y'), array('class' => 'form-control date', 'required'))!!}    
                </div>
                
              </div>
            </div>
          </div>
        
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <button class="btn btn-primary waves-effect" type="button" onclick="gerar()">Gerar</button>
                    <button class="btn-submit btn btn-success waves-effect" type="button">Exportar</button>
                </div>
            </div>
        </div>
        {!!Form::close()!!}
        <div id="response"></div>

    </div>

  </div>

  <script>

    function gerar(){
      token = $("input[type=hidden][name=_token]").val()
  
      $.post( "/admin/estoque/relatorios/transferencias_excel", { _token: $("input[type=hidden][name=_token]").val(), arena: $('#id-field-arena').val(), data_inicial: $('#id-field-data_inicial').val(), data_final: $('#id-field-data_final').val(), tela: true })
        .done(function( data ) {
            $('#response').html(data)
      });
    }

</script>

@stop

@section('script')
@stop