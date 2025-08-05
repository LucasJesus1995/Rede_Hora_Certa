@extends('admin')

@section('content')

    <div class="card">
        <div class="card-heading">
            <h2>Importação (Agendamento)</h2>
            <small></small>
            <hr/>

            <form>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="row" id="">
                    <div class="col-md-10">
                        {!!Form::selectField('periodo', \App\Http\Helpers\Util::anoMes(), Lang::get('app.periodo'), date('Y')."-".date('m'), array('class' => 'form-control chosen','id'=>'periodo'))!!}
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <a id="btn-relatorio-ajax" href="javascript: void(0)" class="btn btn-success form-control">{{Lang::get('app.gerar-relatorio')}}</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="card-body bg-light lt" id="box-grid"></div>
    </div>
@stop
