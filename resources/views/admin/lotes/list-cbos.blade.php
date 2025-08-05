<div id="box-list-cbos">
    <h4>
        CBO's
        <div class="pull-right">
            <a href="javascript: void(0);" class="btn-close-cbos">
                <i class="fa fa-remove"></i>
            </a>
        </div>
    </h4>
    <hr />
    <input type="hidden" id="lote_profissional" value="{!! $lote_profissional->id !!}" />
    <div class="row">
        @if(!empty($cbos))
            @foreach($cbos AS $codigo => $cbo)
                <?php
                    $lote_profissional_cbo = App\LoteProfissionalCbo::getLoteProfissionalCbo2($lote_profissional->lote, $lote_profissional->profissional, $codigo);
                    $checked = !empty($lote_profissional_cbo) ? "checked" : null;
                ?>
                <div class="col-md-6">
                    <small class="text-muted">
                        <input type="checkbox" value="{!! $codigo !!}" {!! $checked !!} class="lote-profissional-cbo" id="{!! $codigo !!}"  data-lote="{!! $lote_profissional->lote !!}" data-profissional="{!! $lote_profissional->profissional !!}"  /> {!! $cbo !!}
                    </small>
                </div>
            @endforeach
        @endif
    </div>
</div>