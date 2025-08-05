<div class="alert alert-info">Informe a data que o paciente será remarcado.</div>
<div class="row" id="box-agenda-remarcacao">
    <input type="hidden" id="agenda" value="{!! $agenda->id !!}" />
    <div class="col-md-12">
        {!!Form::selectField('motivo_remarcacao', \App\Http\Helpers\Util::getMotivosRemarcacao(),"Motivo", null, array('class' => 'form-control chosen motivo_remarcacao'))!!}
    </div>
    <div class="col-md-5">{!!Form::textField('data', Lang::get('app.data'), null, array('class' => 'form-control date'))!!}</div>
    <div class="col-md-3">{!!Form::textField('hora', Lang::get('app.hora'), $time, array('class' => 'form-control time', 'maxlength'=>5))!!}</div>
    <div class="col-md-4"><label>&nbsp;</label>
        <button id="btn-agenda-remarcacao-save" class="btn btn-success waves-effect col-md-12" type="button">{{Lang::get('app.salvar')}}</button>
    </div>
</div>

<script>
    loadingMask();
    loadingDate();
</script>