@extends('admin')

@section('content')

  <div class="card">
    <div class="card-heading">
      <h2>
        Relatório - vencimento de produtos
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

       {!!Form::model('Produtos', array('url' => route('estoque.relatorios.vencimentos_excel'), 'class' => 'form-vertical', 'autocomplete' => 'off'))!!}
        <div class="row">
            <div class="col-md-12">
              <div class="row">   
                <div class="col-md-6">
                  {!!Form::selectField('arena', $arenas, 'Arena', null, array('class' => 'form-control chosen', 'onchange' => 'carregarTransferencias()'))!!}                
                </div>             
                <div class="col-md-6">
                  {!!Form::selectField('vencimento', ['vencidos' => 'VENCIDOS', '10' => 'ATÉ 10 DIAS', '20' => 'ATÉ 20 DIAS', '30' => 'ATÉ 30 DIAS'], 'Vencimento', null, array('class' => 'form-control'))!!}                
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
      $.post( "/admin/estoque/relatorios/vencimentos_excel", { _token: $("input[type=hidden][name=_token]").val(), arena: $('#id-field-arena').val(), vencimento: $('#id-field-vencimento').val(), tela: true })
        .done(function( data ) {
            $('#response').html(data)
      });
    }

</script>

@stop

@section('script')
@stop