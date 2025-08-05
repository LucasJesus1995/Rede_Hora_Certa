<?php
$pos = 5;
$pos_inicial = $pos;
?>
<html>
<link media="all" type="text/css" rel="stylesheet" href="src/css/relatorio/faturamento-metrica-producao.css">

<table border="1">
    <tr>
        <th colspan="7" class="title">Reltório de Faturamento - {!! $lote->nome !!}</th>
    </tr>
    <tr>
        <th colspan="7" class="sub-title"></th>
    </tr>
    <tr></tr>

    <tr>
        <th>Código</th>
        <th>Procedimento</th>
        <th class="fpo">Valor SUS</th>
        <th class="fpo">Qtd. SUS</th>
        <th class="fpo">Total</th>
        <th class="cies">Faturado</th>
        <th class="cies">Valor Faturado</th>
        <td class="no-bg"></td>
        <th>Qtde. (FAT - FPO)</th>
        <th>% Dif.</th>
        <th>Valor (FAT - FPO)</th>
        <th>% Prop.</th>
    </tr>

    @foreach($relatorio AS $row)
        <?php
            $contrato_procedimentos = \App\ContratoProcedimentos::getContratoProcedimentoByContratoProcedimentoLote($contrato, $row->procedimento_id, $lote->id);
        ?>
         <tr class="line {!! ($pos % 2) ? 'odd' : 'even' !!}">
             <td>{!! \App\Http\Helpers\Mask::ProcedimentoSUS($row->procedimento_sus) !!}</td>
             <td>{!! $row->procedimento_nome !!}</td>
             <td class="fpo">
                @if(!empty($contrato_procedimentos->valor_unitario))
                    {!! $contrato_procedimentos->valor_unitario !!}
                @endif
             </td>
             <td class="fpo">
                @if(!empty($contrato_procedimentos->quantidade))
                    {!! $contrato_procedimentos->quantidade !!}
                @endif
             </td>
             <td class="fpo">=C{!! $pos !!}*D{!! $pos !!}</td>
             <td class="cies">
                @if(!empty($row->total))
                    {!! $row->total !!}
                @endif
             </td>
             <td class="cies">=F{!! $pos !!}*C{!! $pos !!}</td>
             <td class="no-bg"></td>
             <td>=F{!! $pos !!}-D{!! $pos !!}</td>
             <td>=IFERROR(I{!! $pos !!}/D{!! $pos !!}, 0)</td>
             <td>=G{!! $pos !!}-E{!! $pos !!}</td>
             <td>=IFERROR(K{!! $pos !!}/$K${!! count($relatorio)+$pos_inicial+1 !!}, 0)</td>
        </tr>
            <?php $pos ++; ?>
    @endforeach
    <tr></tr>
    <tr>
        <td></td>
        <td></td>
        <th class="fpo">=SUM(C{!! $pos_inicial !!}:C{!! ($pos-1) !!})</th>
        <th class="fpo">=SUM(D{!! $pos_inicial !!}:D{!! ($pos-1) !!})</th>
        <th class="fpo">=SUM(E{!! $pos_inicial !!}:E{!! ($pos-1) !!})</th>
        <th class="cies">=SUM(F{!! $pos_inicial !!}:F{!! ($pos-1) !!})</th>
        <th class="cies">=SUM(G{!! $pos_inicial !!}:G{!! ($pos-1) !!})</th>
        <td class="no-bg"></td>
        <th class="sub-title">=SUM(I{!! $pos_inicial !!}:I{!! ($pos-1) !!})</th>
        <td class="no-bg"></td>
        <th class="sub-title">=SUM(K{!! $pos_inicial !!}:K{!! ($pos-1) !!})</th>
    </tr>
    <tr></tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td colspan="2" class="right sub-title-2">Total Faturamento</td>
        <td class="sub-title">=G{!! count($relatorio)+$pos_inicial+1 !!}</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td colspan="2" class="right sub-title-2">Teto Faturamento</td>
        <td class="sub-title">=E{!! count($relatorio)+$pos_inicial+1 !!}</td>
    </tr>
    <tr></tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td colspan="2" class="right sub-title-2">Diferença</td>
        <td class="sub-title">=G{!! count($relatorio)+$pos_inicial+1 !!} - E{!! count($relatorio)+$pos_inicial+1 !!}</td>
    </tr>
    <tr></tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td colspan="2" class="right sub-title-2">Cálculo do Incentivo</td>
        <td class="sub-title">=G{!! count($relatorio)+$pos_inicial+1 !!} * 20 / 100</td>
    </tr>
</table>

</html>

<?php //die; ?>
