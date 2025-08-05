<?php
    //$linha_cuidado = \App\Arenas::getLinhasCuidado($arena->id);

?>
<html>
<link media="all" type="text/css" rel="stylesheet" href="src/css/relatorio/absenteismo.css">

<table border="1">
    <tr></tr>
    <tr>
        <th colspan="6" class="title" style="font-size: 20px; padding: 10px">{!! $arena !!}</th>
    </tr>
    <tr></tr>

    <tr>
        <th>Semana</th>
        <th>Especialidade</th>
        <th>Agendadas</th>
        <th>Faltas</th>
        <th>Atendido</th>
        <th>% de faltas</th>
    </tr>

</table>

</html>