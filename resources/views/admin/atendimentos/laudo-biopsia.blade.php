<div class="">
    <h4>{!! Lang::get('app.laudo') !!}</h4>

    <div>
        @if(!empty($laudos))
            @foreach($laudos AS $laudo)
                <div id="laudo-{!! $laudo['id'] !!}" class="well well-sm">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="alert alert-info">{!! $laudo['biopsia'] !!}</p>
                            <div>
                                {!!Form::textareaField('descricao',Lang::get('app.analise'), $laudo['resultado_biopsia'], array('class'=>'no-style form-control', 'rows'=>'2','id'=>'descricao'))!!}
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    {!!Form::selectField('status_biopsia', \App\Http\Helpers\Util::statusLaudo(), Lang::get('app.status'), $laudo['status_biopsia'], array('class' => 'form-control combo-profissional','id'=>'status_biopsia'))!!}
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>&nbsp;</label><br />
                                        <button rel="{!! $laudo['id'] !!}" class="btn-save-resultado-laudo btn btn-success waves-effect col-md-12" type="button">{{Lang::get('app.salvar')}}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="well well-sm">
                                <div style="height: 220px; overflow: auto">{!! !empty($laudo['descricao']) ? urldecode($laudo['descricao']) : null !!}</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>