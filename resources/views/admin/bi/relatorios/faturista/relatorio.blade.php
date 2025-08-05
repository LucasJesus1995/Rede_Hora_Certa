<?php
    $ultimo_dia_mes = date("t", mktime(0,0,0,$mes,'01',$ano));


?>
<h3>Faturista</h3>
<table class="relatorio-detalhado" border="1" width="100%" >
    @foreach($relatorio_faturista AS $key_mes => $faturistas)
        <?php
        $ultimo_dia_mes = date("t", mktime(0,0,0,$key_mes,'01',$ano));
        ?>
        <thead>
        <tr>
            <th colspan="100%"><strong>{{ \App\Http\Helpers\Util::getMesNome($key_mes)}}</strong></th>
        </tr>
        <tr>
            <th></th>
            @for($i = 1; $i <= $ultimo_dia_mes; $i++)
                <th>{!!  $i !!}</th>
            @endfor
        </tr>
        </thead>
        <tbody>
        <?php
            $_faturistas = \App\Usuarios::getFaturistasCombo();
            $_faturistas[0] = "OUTROS";
        ?>
        @foreach($_faturistas AS $codigo_faturista => $faturista)
            <?php
                $total_mes = [];
            ?>
            <tr>
                <th class="align-left">{!! $faturista !!}</th>
                    @for($i = 1; $i <= $ultimo_dia_mes; $i++)
                        <td>
                            <?php
                                if(array_key_exists($codigo_faturista, $faturistas)){
                                    $dias = $faturistas[$codigo_faturista];
                                    echo $_total =  (is_array($dias) && array_key_exists($i, $dias)) ? array_sum($dias[$i]) : null;
                                    $total_mes[] = $_total;
                                }
                            ?>
                        </td>
                    @endfor
                <th>{!! array_sum($total_mes) !!}</th>
            </tr>
        @endforeach
        </tbody>
    @endforeach
</table>

@section('script')
    $("#btn-relatorio-producao-arena-faturistas").click();
@stop