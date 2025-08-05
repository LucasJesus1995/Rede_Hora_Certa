@extends('admin')

@section('content')

  <div class="card">
    <div class="card-heading">
      <h2>
        Produtos - novo
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

        <div class="row">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-12">
                  {!!Form::selectField('arena', $arenas, 'Arena', null, array('class' => 'form-control', 'onchange' => 'verEstoque()'))!!}                
                </div>
              </div>
            </div>
          </div>
        
        <div class="row" id="estoqueArenas">
            
        </div>
    </div>

  </div>
  <script>
    function verEstoque(){  
        arena = $('#id-field-arena').val()
        if(arena != ''){
          console.log(arena)
          $.get( "/admin/estoque/" + arena + "/arenas-estoque", function( data ) {
              $('#estoqueArenas').html(data)
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