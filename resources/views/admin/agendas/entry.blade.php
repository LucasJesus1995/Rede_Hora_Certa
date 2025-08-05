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
            @if(isset($entry->id))
                <div class="alert alert-danger"><strong>Atenção</strong><br/>Ao alterar uma especialidade todos os procedimentos, anamnese, laudo e dados referentes a este
                    atendimento será removido tendo a necessidade de uma nova inserção, devido as variações das configurações dos dados.
                </div>
            @endif
            {!!Form::model($entry, array('url' => '/admin/agendas', 'class' => 'form-vertical'))!!}
            {!!Form::hidden('id')!!}
            <div class='row'>
                <div class='col-md-6'>
                    {!!Form::textField('paciente', Lang::get('app.pacientes'), null, array('class' => 'form-control autocomplete-pacientes'))!!}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                @if(!empty($entry->id))
                                    <div class="col-md-4">{!!Form::textField('data', Lang::get('app.data'), null, array('class' => 'form-control date', 'disabled' => true))!!}</div>
                                @else
                                    <div class="col-md-4">{!!Form::textField('data', Lang::get('app.data'), null, array('class' => 'form-control date'))!!}</div>
                                @endif
                                <div class="col-md-2">{!!Form::textField('hora', Lang::get('app.hora'), null, array('class' => 'form-control time'))!!}</div>
                                    <div class="col-md-6">{!!Form::selectField('tipo_atendimento', \App\Http\Helpers\Util::getTipoAtendimento(), "Tipo de Agendamento", null, array('class' => 'form-control chosen'))!!}</div>
                            </div>

                        </div>
                    </div>
                    {!!Form::selectField('estabelecimento', App\Estabelecimento::Combo(), Lang::get('app.estabelecimento'), null, array('class' => 'chosen'))!!}
                </div>
                <div class='col-md-6'>
                    {!!Form::selectField('arena', \App\Arenas::Combo(), Lang::get('app.arenas'), null, array('class' => 'form-control combo-arena combo-arena-equipamentos  chosen'))!!}

                    <div class="row">
                        <div class="col-md-6">
                            {!!Form::selectField('linha_cuidado', [], Lang::get('app.linha-cuidado'), null, array('class' => 'form-control linha_cuidado combo-especialidade-profissionais'))!!}
                            {!!Form::hidden('linha_cuidado', null, ['id'=>'combo-linha_cuidado'])!!}
                        </div>
                        <div class="col-md-6">
                            {!!Form::selectField('arena_equipamento', [],"Equipamento", null, array('class' => 'form-control chosen equipamento','id' => 'equipamento'))!!}
                            {!!Form::hidden('arena_equipamento', null, ['id'=>'combo-equipamento'])!!}
                        </div>
                    </div>

                    {!!Form::selectField('procedimento', \App\Procedimentos::ComboConsolidados([44,46,53]), Lang::get('app.procedimentos')." (Somente consolidados)", null, array('class' => 'form-control chosen'))!!}
                    {!!Form::selectField('medico', [], Lang::get('app.profissional'), null, array('class' => 'form-control profissionais chosen'))!!}
                    {!!Form::hidden('medico', null, ['id'=>'combo-profissional'])!!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <hr/>
                    <div class="form-group">
                        <button class="btn-back-listagem btn btn-default waves-effect" type="button">{{Lang::get('app.cancelar')}}</button>
                        <button class="btn-submit btn btn-success waves-effect" type="button">{{Lang::get('app.salvar')}}</button>
                    </div>
                </div>
            </div>
            {!!Form::close()!!}
        </div>

    </div>

@stop

@section('script')
    $(".combo-arena").change();

    setTimeout(function(){
        $("select.linha_cuidado").change();

        setTimeout(function(){
            $("select.profissionais").val($("#combo-profissional").val());
            $("select").trigger("chosen:updated");
        }, 700);
    }, 700);

@stop