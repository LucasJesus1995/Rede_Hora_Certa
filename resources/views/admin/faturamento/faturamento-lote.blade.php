<?php
    $faturamento_lote = \App\FaturamentoLotes::getFaturamentLoteByFaturamento($faturamento->id);
?>

<div class="md-whiteframe-z0 bg-white">
    <ul class="nav nav-lines nav-tabs nav-justified nav-atendimento">
        <?php $i = 0;?>
        @foreach($faturamento_lote AS $lote)
            <li class="{!! ($i == 0) ? 'active' : null !!}">
                <a class="text-sm btn btn-lg btn-rounded btn-stroke btn-info m-r waves-effect" data-target="#tab-lote-{!! $lote->id !!}" data-toggle="tab" href="" data-atendimento="">
                    <span class="block clear text-left m-v-xs"> {!! $faturamento->ano !!}/{!! $faturamento->mes !!}<b class="text-lg block font-bold">{!! $lote->nome !!}</b></span>
                </a>
            </li>
            <?php $i++;?>
        @endforeach
    </ul>

    <div class="tab-content p m-b-md clear b-t b-t-2x">
        <?php $i = 0;?>
        @foreach($faturamento_lote AS $lote)
            <div id="tab-lote-{!! $lote->id !!}" class="tab-pane animated fadeInDown {!! ($i == 0) ? 'active' : null !!}" role="tabpanel">
                {!! $lote->nome !!}
                @include('admin.faturamento.lote-linha-cuidado',['lote'=>$lote->id,'faturamento_lote'=>$lote->faturamento_lote])
            </div>
                <?php $i++;?>
        @endforeach
    </div>
</div>
