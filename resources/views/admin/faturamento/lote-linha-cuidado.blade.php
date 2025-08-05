<?php
    $lote = \App\Lotes::find($lote);
    $linhas_cuidado = \App\LinhaCuidado::Combo();
?>
<div class="card">
    <div class="card-heading">
      <h2>{{$lote->codigo}} - {{$lote->nome}}</h2>
      <small>Gerenciamento de metas por especialidade</small>
    </div>

    <div class="card-body bg-light lt" id="box-grid">
        <div class="text-center m-b">
            <input type="hidden" name="lote" id="lote" value="{{$lote->id}}" />
            @if(!empty($linhas_cuidado))
                <table class="table table-striped table-responsive table-bordered  bg-light " >
                    <thead>
                    <tr role="row">
                        <th rowspan="2" valign="middle" >Especialidade</th>
                        <th colspan="3" class="text-center">Meta</th>
                    </tr>
                    <tr>
                        <th class="text-center">Min</th>
                        <th class="text-center">Média</th>
                        <th class="text-center">Max</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($linhas_cuidado AS $codigo_linha_cuidado => $linha_cuidado)
                        <?php
                            $lotes_linha_cuidado = \App\LotesLinhaCuidado::getByFaturamentoLoteLinhaCuidado($faturamento_lote, $codigo_linha_cuidado);

                            $minimo = !empty($lotes_linha_cuidado->minimo) ?  $lotes_linha_cuidado->minimo : null;
                            $medio = !empty($lotes_linha_cuidado->media) ? $lotes_linha_cuidado->media : null;
                            $maximo = !empty($lotes_linha_cuidado->maximo) ?  $lotes_linha_cuidado->maximo : null;
                        ?>
                        <tr class="btn-lote-linha_cuidado">
                            <td valign="middle" class="text-left">{{ $linha_cuidado }}</td>
                            <td width="15%"><input name="minimo" value="{{$minimo}}" maxlength="6" type="text" data-linha-cuidado="{{ $codigo_linha_cuidado }}" data-faturamento-lote="{{$faturamento_lote}}" class="col-md-12 " data-key="minimo" /></td>
                            <td width="15%"><input name="medio" value="{{$medio}}" maxlength="6" type="text" data-linha-cuidado="{{ $codigo_linha_cuidado }}" data-faturamento-lote="{{$faturamento_lote}}" class="col-md-12 " data-key="media" /></td>
                            <td width="15%"><input name="maximo" value="{{$maximo}}" maxlength="6"  type="text" data-linha-cuidado="{{ $codigo_linha_cuidado }}" data-faturamento-lote="{{$faturamento_lote}}" class="col-md-12 " data-key="maximo" /></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>