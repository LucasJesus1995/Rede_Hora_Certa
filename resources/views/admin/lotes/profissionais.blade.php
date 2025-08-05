<div>
    <h3>{!! $lote->nome !!}</h3>
    <div>
        <div class="row" id="box-form-profissional">
            <div class="col-md-10">
                {!!Form::selectField('profissional', \App\Profissionais::ComboMedicos(), Lang::get('app.profissional'), null, array('class' => 'form-control chosen','id'=>'profissional'))!!}
                {!!Form::hidden('id', $lote->id, array('id'=>'lote'))!!}
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <button class="btn-submit-lote-profissional btn btn-success waves-effect col-md-12" type="button" style="margin-top: 24px">{{Lang::get('app.salvar')}}</button>
                </div>
            </div>
        </div>
    </div>
    <hr />

    <div id="box-grid-profissionais"></div>
</div>

<script type="text/javascript">
    atualizaGridLoteProfissionais();
</script>