@extends('admin')

@section('content')


    <div class="card">
        <div class="card-heading">
            <h2>
                Relatório de Aderência (Digitador / Recepção)
            </h2>
            <small></small>
            <hr />

            <div id="relatorio-aderencia-digitador">
                <form>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="row" >
                        <div class="col-md-6">
                            {!! Form::selectField('arena', \App\Arenas::Combo(), Lang::get('app.arenas'), null, array('class' => 'form-control combo-arena chosen','id'=>'arena')) !!}
                        </div>
                        <div class="col-md-2">
                            {!!Form::textField('data_inicial', Lang::get('app.data-inicial'), date('01/m/Y'), array('class' => 'form-control date','id'=>'data_inicial'))!!}
                        </div>
                        <div class="col-md-2">
                            {!!Form::textField('data_final', Lang::get('app.data-final'), date('d/m/Y'), array('class' => 'form-control date','id'=>'data_final'))!!}
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <a id="btn-relatorio-aderencia-digitador" href="javascript: void(0)" class="btn btn-success form-control">{{Lang::get('app.gerar')}}</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card-body bg-light lt" id="box-grid">
            <div class="text-center m-b">
                <div class="alert alert-info">{{Lang::get('app.selecione-os-parametros-para-pesquisa')}}</div>
            </div>
        </div>
    </div>
@stop

@section('script')
    $(".combo-arena").change();
@stop