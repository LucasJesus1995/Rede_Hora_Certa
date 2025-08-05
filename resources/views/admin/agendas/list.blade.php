@extends('admin')

@section('content')
    <div class="card">
    <div class="card-heading">
      <h2>
        {{Lang::get('app.listagem-de-dados')}}
      </h2>
      <small>{{Lang::get('description.agenda')}}</small>
    </div>
    <div class="card-tools">
        <a href="" class="btn-new-entry btn btn-default">{{Lang::get('app.nova-agenda')}}</a>
    </div>
    <div class="card-body bg-light lt" >

        {!!Form::open( array('class' => 'form-vertical','id' => 'form-atendimento','method'=>'GET'))!!}
            <div class="row">
                <div class="col-md-3">
                    {!!Form::selectField('arena', \App\Arenas::Combo(), Lang::get('app.arenas'), App\Http\Helpers\Util::setCookie('agenda-pesquisa-arena'), array('class' => 'form-control combo-arena chosen','id'=>'arena'))!!}
                </div>
                <div class="col-md-3">
                    {!!Form::selectField('linha_cuidado', [], Lang::get('app.linha-cuidado'), null, array('class' => 'form-control linha_cuidado','id' => 'linha_cuidado'))!!}
                </div>
                <div class="col-md-2">
                    {!!Form::textField('paciente', Lang::get('app.paciente'), null, array('class' => 'form-control','id'=>'paciente'))!!}
                    <span class='text-descricao-input text-muted block text-xs'>(Nome, CPF, CNS)</span>
                </div>
                <div class="col-md-2">
                    {!!Form::textField('data', Lang::get('app.data'), date('d/m/Y'), array('class' => 'form-control date','id'=>'data'))!!}
                </div>
                <div class="col-md-2">
                    <div class="align-center">
                        <div class="form-group">
                            <label class="" style="display: block;" >&nbsp;</label>
                            <div class="row">
                                <div class="col-md-5"><a id="btn-print-agenda" class="btn col-md-12 btn-success waves-effect"><i class="fa fa-print"></i></a></div>
                                <div class="col-md-7"><a id="btn-search-agenda" class="btn  col-md-12 btn-info waves-effect"><i class="fa fa-search"></i></a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        {!!Form::close()!!}

        @if(in_array(Auth::user()->profile, array(1, 2, 3, 4, 6, 5)))
            <div class="alert alert-danger" id="info-agenda">
                <?php
                    switch(Auth::user()->profile){
                        case 1:
                            echo "Seu acesso só permite ter ação no paciente após a recepção!";
                        break;
                        case 2:
                            echo "Seu acesso só permite ter ação no paciente após a enfermagem!";
                        break;
                        case 3:
                             echo "Acesso administrador, você pode visualizar tudo, porém não edite as informações de atendimento!";
                        break;
                        case 4:
                        case 6:
                             echo "Seu acesso só permite gerenciar a aba recepção!";
                        break;
                        case 5:
                             echo "Perfil de acesso Tecnico em Radiologia";
                        break;
                    }
                ?>
            </div>
        @endif
    </div>
    <div id="box-grid">
        <div class="text-center m-b" style="padding: 30px">
          <i class="fa fa-circle-o-notch fa-spin text-lg text-muted-lt"></i>
        </div>
    </div>
    </div>
  </div>

@stop

@section('script')
$(".combo-arena").change();
$("#btn-search-agenda").click();
@stop
