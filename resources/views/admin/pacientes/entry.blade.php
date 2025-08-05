@extends('admin')

@section('content')

    <div class="card">
        <div class="card-heading">
            <h2>
                {{Lang::get('app.listagem-de-dados')}}
            </h2>
            <small>{{Lang::get('description.paciente')}}</small>
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
        <div class="card-body bg-light " id="box-paciente">
            {!!Form::model($entry, array('url' => '/admin/pacientes', 'class' => 'form-vertical'))!!}
            {!!Form::hidden('id')!!}
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-4">
                            {!!Form::textField('cns', Lang::get('app.cns'), null, array('class' => 'form-control cns-service number', 'maxlength'=> 15))!!}
                        </div>
                        <div class="col-md-8">
                            {!!Form::textField('nome', Lang::get('app.nome'), null, array('class' => 'form-control'))!!}
                        </div>
                    </div>
                    {!!Form::textField('nome_social', 'Nome Social', null, array('class' => 'form-control'))!!}
                    {!!Form::textField('mae', Lang::get('app.nome-mae'), null, array('class' => 'form-control'))!!}
                    <div class="row">
                        <div class="col-md-6">
                            {!!Form::textField('nascimento', Lang::get('app.nascimento'), null, array('class' => 'form-control date'))!!}
                            {!!Form::textField('cpf', Lang::get('app.cpf'), null, array('class' => 'form-control cpf'))!!}
                            {!!Form::selectField('nacionalidade', App\Http\Helpers\Util::Nacionalidade(), Lang::get('app.nacionalidade'), "010", array('class' => 'form-control'))!!}
                            {!!Form::selectField('raca_cor', App\Http\Helpers\Util::RacaCor(), Lang::get('app.raca-cor'), null, array('class' => 'form-control'))!!}
                        </div>
                        <div class="col-md-6">
                            {!!Form::selectField('sexo', \App\Http\Helpers\Util::Sexo(), Lang::get('app.sexo'), null, array('class' => 'form-control'))!!}
                            {!!Form::textField('rg', Lang::get('app.rg'), null, array('class' => 'form-control'))!!}
                            {!!Form::selectField('estado_civil', \App\Http\Helpers\Util::EstadoCivil(), Lang::get('app.estado-civil'), null, array('class' => 'form-control'))!!}

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            {!!Form::textField('celular', Lang::get('app.celular'), null, array('class' => 'form-control cell-phone', 'placeholder'=> '(99) 99999-9999'))!!}
                        </div>
                        <div class="col-md-6">
                            {!!Form::textField('telefone_residencial', Lang::get('app.telefone-residencial'), null, array('class' => 'form-control phone'))!!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            {!!Form::textField('email', Lang::get('app.email'), null, array('class' => 'form-control lowercase'))!!}
                            {!!Form::textField('contato', Lang::get('app.contato'), null, array('class' => 'form-control '))!!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-6">
                            {!!Form::textField('cep', Lang::get('app.cep'), null, array('class' => 'form-control cep'))!!}
                        </div>
                        <div class="col-md-6">
                            {!!Form::selectField('endereco_tipo', App\Http\Helpers\Util::EnderecoTipo(), Lang::get('app.tipo'), "081", array('class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-9">
                            {!!Form::textField('endereco', Lang::get('app.endereco'), null, array('class' => 'form-control'))!!}
                        </div>
                        <div class="col-md-3">
                            {!!Form::textField('numero', Lang::get('app.numero'), null, array('class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            {!!Form::selectField('estado', \App\Estados::Combo(), Lang::get('app.estado'), "26", array('class' => 'chosen combo-estado'))!!}
                            {!!Form::textField('bairro', Lang::get('app.bairro'), null, array('class' => 'form-control'))!!}
                        </div>
                        <div class="col-md-7">
                            {!!Form::hidden('cidade', null, ['id'=>'combo-cidade'])!!}
                            {!!Form::selectField('cidade', isset($cidades) ? $cidades : [], Lang::get('app.cidade'), null, array('class' => 'chosen combo-cidade'))!!}
                            {!!Form::textField('complemento', Lang::get('app.complemento'), null, array('class' => 'form-control lowercase'))!!}
                        </div>
                    </div>

                    {!!Form::selectField('ativo', array('1' => Lang::get('app.sim'), '0' => Lang::get('app.nao')), Lang::get('app.ativo'), "1", array('class' => 'form-control'))!!}
                    {!!Form::selectField('estabelecimento', App\Estabelecimento::Combo(), Lang::get('app.estabelecimento'), null, array('class' => 'chosen'))!!}
                    {!!Form::textareaField('descricao',Lang::get('app.descricao'), null, array('class'=>'no-style form-control', 'rows'=>'5'))!!}
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
        </div>
    </div>

@stop

@section('script')
    $(".combo-estado").change();
@stop