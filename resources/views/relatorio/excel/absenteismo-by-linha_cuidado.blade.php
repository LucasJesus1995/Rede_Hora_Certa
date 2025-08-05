<?php
    $linha_cuidado = \App\Arenas::getLinhasCuidado($arena->id);
?>
<html>
<link media="all" type="text/css" rel="stylesheet" href="src/css/relatorio/absenteismo.css">

<table border="1">
    <tr>
        <th colspan="{!! (count($dias) * 4)+5 !!}" class="title" style="font-size: 20px; padding: 10px">ABSENTEÍSMO - {!! $arena->nome !!}</th>
    </tr>
    <tr></tr>

    <tr>
        <th>ESPECIALIDADES</th>
        <th nowrap colspan="4">ACUMULADO - SEMANA</th>
        @foreach($dias AS $semana => $dia)
            <th nowrap colspan="4">{!! \App\Http\Helpers\Util::diaSemana($semana) !!} - {!! \App\Http\Helpers\Util::DB2User($dia) !!}</th>
        @endforeach
    </tr>

    <tr>
        <th></th>
        <th>AG</th>
        <th>FT</th>
        <th>AT</th>
        <th>% FT</th>
        @foreach($dias AS $semana => $dia)
            <th>AG</th>
            <th>FT</th>
            <th>AT</th>
            <th>% FT</th>
        @endforeach
    </tr>

    @if(!empty($linha_cuidado))
        <?php
            $i = 5;
            $i_inicio = $i;

            $i_dias[] = "F:G";
            $i_dias[] = "J:K";
            $i_dias[] = "N:O";
            $i_dias[] = "R:S";
            $i_dias[] = "V:W";
            $i_dias[] = "Z:AA";
            $i_dias[] = "AD:AE";
        ?>
        @foreach($linha_cuidado AS $codigo_linha_cuidado => $nome_linha_cuidado)
            <tr class="line {!! ($i % 2) ? 'odd' : 'even' !!}">
                <th class="left">{!! $nome_linha_cuidado !!}</th>
                <td class="center" width="7">
                    <?php
                        $column = 0;
                        $_sum_semana = [];
                        foreach($dias AS $semana => $dia) :
                            $col = explode(":", $i_dias[$column]);
                            $_sum_semana[] = "{$col[0]}{$i}";
                            $column++;
                        endforeach;
                        echo "=(".implode("+",$_sum_semana).")";
                    ?>
                </td>
                <td class="center" width="7">
                    <?php
                    $column = 0;
                    $_sum_semana = [];
                    foreach($dias AS $semana => $dia) :
                        $col = explode(":", $i_dias[$column]);
                        $_sum_semana[] = "{$col[1]}{$i}";
                        $column++;
                    endforeach;
                    echo "=(".implode("+",$_sum_semana).")";
                    ?>
                </td>
                <td class="center" width="7">
                    =(B{!! $i !!}-C{!! $i !!})
                </td>
                <td class="center" width="7">
                    =IF(C{!! $i !!}>0,(ROUND(((C{!! $i !!}/B{!! $i !!})*100),2)),0)
                </td>

                <?php $column = 0; ?>
                @foreach($dias AS $semana => $dia)
                    <?php
                        $data = \App\Agendas::getAbsenteismo($dia, $codigo_linha_cuidado, $arena->id);
                        $falta = (!empty($data['falta'])) ? count($data['falta']) : 0;
                        $atendido = (!empty($data['atendido'])) ? count($data['atendido']) : 0;
                        $atendimentos = ($falta+$atendido);

                        $percentual_falta = \App\Http\Helpers\Util::getPorcentagem($falta, $atendimentos);
                    ?>
                    <td class="center atendimento" width="7">{!! $atendimentos !!}</td>
                    <td class="center falta" width="7">{!! $falta !!}</td>
                    <td class="center atendido" width="7">{!! $atendido !!}</td>
                    <td class="center total" width="7">
                        <?php
                            $col = explode(":", $i_dias[$column]);
                            echo "=IF({$col[1]}{$i}>0,(ROUND((({$col[1]}{$i}/{$col[0]}{$i})*100),2)),0)";
                            $column++;
                        ?>
                    </td>
                @endforeach
            </tr>

            <?php $i++; ?>
        @endforeach
            <?php
                $line = $i - 1;
            ?>
            <tr>
                <td></td>
                <td class="center">=SUM(B{!! $i_inicio !!}:B{!! $line !!})</td>
                <td class="center">=SUM(C{!! $i_inicio !!}:C{!! $line !!})</td>
                <td class="center">=SUM(B{!! $i !!}-C{!! $i !!})</td>
                <td class="center">=IF(C{!! $i !!}>0,(ROUND(((C{!! $i !!}/B{!! $i !!})*100),2)),0) </td>
                <?php $column = 0; ?>
                @foreach($dias AS $semana => $dia)
                    <?php
                        $col = explode(":", $i_dias[$column]);

                    ?>
                    <td class="center">  <?php echo "=SUM({$col[0]}{$i_inicio}:{$col[0]}{$line})"; ?> </td>
                    <td class="center">  <?php echo "=SUM({$col[1]}{$i_inicio}:{$col[1]}{$line})"; ?> </td>

                    <td class="center">  =({!! $col[0]!!}{!! $i !!}-{!!  $col[1]!!}{!! $i !!}) </td>
                    <td class="center">  =IF({!! $col[1]!!}{!! $i !!}>0,(ROUND((({!! $col[1]!!}{!! $i !!}/{!! $col[0]!!}{!! $i !!})*100),2)),0)  </td>
                    <?php $column++;?>
                @endforeach
            </tr>
            <?php $i++; ?>
    @endif

</table>

</html>

<?php // die;?>