@extends('admin')

@section('content')

    <div class="card">
        <div class="card-heading">
            <h2>
                {{Lang::get('app.listagem-de-dados')}}
            </h2>
            <small>{{Lang::get('description.procedimento')}}</small>
        </div>
        <div class="card-tools">
            <ul class="list-inline">
                <li class="dropdown">
                    <a class="md-btn md-flat md-btn-circle waves-effect" data-toggle="dropdown" md-ink-ripple=""
                       aria-expanded="false">
                        <i class="mdi-navigation-more-vert text-md"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-scale pull-right pull-up top text-color">
                        <li><a href="" class="btn-back-listagem ">{{Lang::get('app.listagem')}}</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="card-body bg-light " id="">

            {!!Form::model($entry, array('url' => '/admin/procedimentos', 'class' => 'form-vertical'))!!}
            {!!Form::hidden('id')!!}
            <div class="row">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-8">
                            {!!Form::textField('nome', Lang::get('app.nome'), null, array('class' => 'form-control'))!!}
                        </div>
                        <div class="col-md-2">
                            {!!Form::selectField('complexidade', \App\ProcedimentoComplexidade::Combo(), 'Complexidade', null, array('class' => 'form-control'))!!}
                        </div>
                        <div class="col-md-2">
                            {!!Form::selectField('modalidade', \App\ProcedimentoModalidade::Combo(), 'Modalidade', null, array('class' => 'form-control'))!!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                        <!-- {!!Form::textField('quantidade', Lang::get('app.quantidade'), null, array('class' => 'form-control'))!!} -->
                            {!!Form::selectField('valida_etapa', App\Http\Helpers\Util::ValidaEtapas(), Lang::get('app.valida-etapa'), null, array('class' => 'form-control'))!!}
                            {!!Form::textField('maximo', Lang::get('app.maximo'), null, array('class' => 'form-control'))!!}

                            <div class="row">
                                <div class="col-md-6">
                                    {!!Form::textField('cbo', Lang::get('app.cbo'), null, array('class' => 'form-control'))!!}
                                </div>
                                <div class="col-md-6">
                                    {!!Form::selectField('sexo', App\Http\Helpers\Util::SexoProcedimento(), "Sexo", null, array('class' => 'form-control'))!!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            {!!Form::selectField('obrigatorio', App\Http\Helpers\Util::Ativo(), Lang::get('app.obrigatorio'), null, array('class' => 'form-control'))!!}
                            {!!Form::selectField('forma_faturamento', App\Http\Helpers\Util::FormaFaturamento(), Lang::get('app.forma-faturamento'), null, array('class' => 'form-control'))!!}

                            <div class="row">
                                <div class="col-md-6">
                                    {!!Form::selectField('ativo', App\Http\Helpers\Util::Ativo(), Lang::get('app.ativo'), null, array('class' => 'form-control'))!!}
                                </div>
                                <div class="col-md-6">
                                    {!!Form::selectField('operacional', App\Http\Helpers\Util::Ativo(), 'Operacional', null, array('class' => 'form-control'))!!}
                                </div>
                            </div>

                        </div>
                        <div class="col-md-4">
                            {!!Form::textField('sus', Lang::get('app.sus'), null, array('class' => 'form-control'))!!}
                            {{--{!!Form::textField('saldo', Lang::get('app.saldo'), null, array('class' => 'form-control'))!!}--}}
                            <div class="row">
                                <div class="col-md-6">{!!Form::selectField('contador', App\Http\Helpers\Util::Ativo(), "Contador", null, array('class' => 'form-control'))!!}</div>
                                <div class="col-md-6">{!!Form::textField('ordem', Lang::get('app.ordem'), null, array('class' => 'form-control'))!!}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">{!!Form::textField('multiplicador', Lang::get('app.multiplicador'), null, array('class' => 'form-control numbers','maxlength'=>2))!!}</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            {!!Form::textField('servico_bpa', Lang::get('app.servico_bpa'), null, array('class' => 'form-control'))!!}
                        </div>
                        <div class="col-md-4">
                            {!!Form::textField('class_bpa', Lang::get('app.classificacao_bpa'), null, array('class' => 'form-control'))!!}
                        </div>
                        <div class="col-md-2">
                            {!!Form::selectField('autorizacao', App\Http\Helpers\Util::Ativo(), 'Autorização', null, array('class' => 'form-control'))!!}
                        </div>
                        <div class="col-md-2">
                            {!!Form::selectField('principal', App\Http\Helpers\Util::Ativo(), 'Principal', null, array('class' => 'form-control'))!!}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    {!!Form::selectMultipleField('linha_cuidado', \App\LinhaCuidado::Combo(), Lang::get('app.linha-cuidado'), null, array('class'=>'no-style form-control span12','style'=>'height: 180px'))!!}
                    {!!Form::selectField('cid_primario', \App\Cid::Combo(), 'CID Primário', null, array('class' => 'form-control chosen'))!!}
                    {!!Form::selectField('cid_secundario', \App\Cid::Combo(), 'CID Secundário', null, array('class' => 'form-control chosen'))!!}
                </div>
            </div>
            <hr />
            <div class="row">
                <div class="col-md-4">
                    {!!Form::selectField('obrigar_preenchimento_apac', App\Http\Helpers\Util::Ativo(), 'Obrigar N&ordm; APAC', null, array('class' => 'form-control'))!!}
                </div>
                <div class="col-md-4"></div>
                <div class="col-md-4"></div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <button class="btn-back-listagem btn btn-default waves-effect"
                                type="button">{{Lang::get('app.cancelar')}}</button>
                        <button class="btn-submit btn btn-success waves-effect"
                                type="button">{{Lang::get('app.salvar')}}</button>
                    </div>
                </div>
            </div>
            {!!Form::close()!!}
        </div>

    </div>

@stop

@section('script')
@stop