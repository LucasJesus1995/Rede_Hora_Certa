<html>
<link media="all" type="text/css" rel="stylesheet" href="src/css/relatorio/diretoria.css">

<table width="100%" border="1">
    <tr>
        <th width="30">RESUMO</th>
        <th width="20">MENSAL</th>
        <th width="25">ANUAL</th>
    </tr>
    <tr>
        <td>SIA Média Complexidade</td>
        <td>=SUMIFS(FPOS!G:G,FPOS!B:B,"SIA",FPOS!D:D,"MC")</td>
        <td>=B2*12</td>
    </tr>
    <tr>
        <td>SIA Alta Complexidade</td>
        <td>=SUMIFS(FPOS!G:G,FPOS!B:B,"SIA",FPOS!D:D,"AC")</td>
        <td>=B3*12</td>
    </tr>
    <tr>
        <td>SIA / FAEC</td>
        <td>=SUMIFS(FPOS!G:G,FPOS!B:B,"FAEC",FPOS!D:D,"MC")</td>
        <td>=B4*12</td>
    </tr>
    <tr>
        <td>SIA / NSA</td>
        <td>=SUMIFS(FPOS!G:G,FPOS!B:B,"NSA",FPOS!D:D,"MC")</td>
        <td>=B5*12</td>
    </tr>
    <tr>
        <th class="left">TOTAL SIA</th>
        <th>=SUM(B2:B5)</th>
        <th>=SUM(C2:C5)</th>
    </tr>
    <tr>
        <td>SIH/HD Média Complexidade</td>
        <td>=SUMIFS(FPOS!G:G,FPOS!B:B,"HD",FPOS!D:D,"MC")</td>
        <td>=B7*12</td>
    </tr>
    <tr>
        <th class="left">TOTAL SIH</th>
        <th>=B7</th>
        <th>=B8*12</th>
    </tr>
    <tr>
        <th class="left">TOTAL FONTE 02</th>
        <th>=B8+B6</th>
        <th>=B9*12</th>
    </tr>
    <tr>
        <td>Incentivo 100% SUS</td>
        <td>=B9*C14</td>
        <td>=B10*12</td>
    </tr>
    <tr>
        <th class="left">TOTAL FONTE 00</th>
        <th>=B10</th>
        <th>=B11*12</th>
    </tr>
    <tr>
        <th class="left">TOTAL (FONTE 00 + FONTE 02)</th>
        <th>=B11+B9</th>
        <th>=B12*12</th>
    </tr>
    <tr></tr>
    <tr>
        <td></td>
        <td class="right">Acrescimo</td>
        <td>0.0710</td>
    </tr>
</table>
</html>