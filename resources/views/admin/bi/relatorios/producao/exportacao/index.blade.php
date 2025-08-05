@extends('admin')

@section('content')

    <div class="card">
        <div class="card-heading">
            <h2>
                Relatorio de Produção
            </h2>
            <small></small>
            <hr />

            <div id="relatorio-producao">
                <form>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="row" >
                        <div class="col-md-4">
                            <?php echo Form::selectField('lote', \App\Lotes::Combo(), Lang::get('app.lotes'), null, array('class' => 'form-control chosen', 'id'=>'lote')); ?>
                        </div>
                        <div class="col-md-3">
                            {!!Form::textField('data[inicial]', Lang::get('app.data-inicial'), date('01/m/Y'), array('class' => 'form-control date','id'=>'data'))!!}
                        </div>
                        <div class="col-md-3">
                            {!!Form::textField('data[final]', Lang::get('app.data-final'), date('d/m/Y'), array('class' => 'form-control date','id'=>'data'))!!}
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <a id="btn-gerar-relatorio-producao-exportacao" href="javascript: void(0)" class="btn btn-success form-control">{{Lang::get('app.gerar-relatorio')}}</a>
                            </div>
                        </div>
                    </div>
            </div>
            </form>
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