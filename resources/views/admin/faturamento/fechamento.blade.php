    @extends('admin')
@section('content')
    <div class="card">
        <div class="card-heading">
            <h2>
                {{Lang::get('app.listagem-de-dados')}}
            </h2>
            <small></small>
        </div>
        <div class="card-tools">
        </div>
        <div class="card-body bg-light lt">
            {!!Form::open( array('class' => 'form-vertical','id' => 'form-fechamento','method'=>'GET'))!!}
            <div class="row">
                <div class="col-md-5">
                    {!!Form::selectField('arena', $arenas, Lang::get('app.arenas'), null, array('class' => 'form-control combo-arena chosen','id'=>'arena'))!!}
                </div>
                <div class="col-md-4">
                    {!!Form::selectField('linha_cuidado', [], Lang::get('app.linha-cuidado'), null, array('class' => 'form-control linha_cuidado','id' => 'linha_cuidado'))!!}
                </div>
                <div class="col-md-2">
                    {!!Form::textField('data', Lang::get('app.data'), date('d/m/Y'), array('class' => 'form-control date','id'=>'data'))!!}
                </div>
                <div class="col-md-1">
                    <div class="align-center">
                        <div class="form-group">
                            <label class="" style="display: block;">&nbsp;</label>
                            <a id="btn-faturamento-fechamento" class="btn btn-success col-md-12 waves-effect"><i
                                        class="fa fa-search"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-5">
                    {!!Form::selectMultipleField('status', \App\Http\Helpers\Util::StatusAgenda(), Lang::get('app.status'), null, array('class' => 'form-control chosen','id'=>'status'))!!}
                </div>
                <div class="col-md-6">
                    {!!Form::selectField('linha_cuidado', \App\Profissionais::ComboMedicos(), 'Profissional', null, array('class' => 'form-control chosen','id' => 'profissional'))!!}
                </div>
            </div>
            {!!Form::close()!!}
        </div>
        <div id="box-grid">
             <div class="alert alert-info margin15">Informe os dados para pesquisa.</div><br />
        </div>
    </div>
@stop
@section('script')
    $(".combo-arena").change();
@stop