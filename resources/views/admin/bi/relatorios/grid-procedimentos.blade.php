<?php
$ultimo_dia_mes = date("t", mktime(0, 0, 0, $mes, '01', $ano));

if (!empty($linha_cuidado)) {
    $linhas_cuidado[] = (Object)\App\LinhaCuidado::get($linha_cuidado);
} else {
    $linhas_cuidado = \App\Arenas::getLinhasCuidado($arena->id, true);
}
?>

<table class="relatorio-detalhado" border="1" width="100%">
    <table class="relatorio-detalhado" border="1" width="100%">
        <thead>
        <tr role="row">
            <th rowspan="2">{{$ano}}</th>
            <th colspan="2">{{ \App\Http\Helpers\Util::getMesNome($mes)}}</th>
            @for($i = 1; $i <= $ultimo_dia_mes; $i++)
                <th rowspan="2">{{ str_pad($i, 2, "0", STR_PAD_LEFT)}}</th>
            @endfor
            <th rowspan="2">{!!Lang::get('app.total')!!}</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $total_geral = 0;
        if(!empty($linhas_cuidado)){
        foreach($linhas_cuidado AS $linha_cuidado){
        $total_geral = 0;

        $sql = \App\Agendas::distinct()->select('procedimentos.id', 'procedimentos.nome')
            ->join('atendimento', 'atendimento.agenda', '=', 'agendas.id')
            ->join('atendimento_procedimentos', 'atendimento_procedimentos.atendimento', '=', 'atendimento.id')
            ->join('procedimentos', 'procedimentos.id', '=', 'atendimento_procedimentos.procedimento')
            ->where('agendas.linha_cuidado', $linha_cuidado->id)
            ->where('agendas.arena', $arena->id)
            ->where('procedimentos.ativo', 1)
            ->whereNotIn('procedimentos.id', [11, 12])
            ->where('procedimentos.contador', 1)
            ->orderBy('procedimentos.ordem', 'asc');


        $procedimentos = $sql->get()->toArray();

        $total_mes_anterior = 0;
        $_total_procedimento = 0;
        ?>
        @if(!empty($procedimentos))
            <tr>
                <th colspan="100%" class="text-left" style="color: #0f0f0f; background: #4CAF50"><strong>{{$linha_cuidado->nome}}</strong></th>
            </tr>

            @foreach($procedimentos AS $_procedimento_id => $row_procedimento)
                <?php
                $total_procedimento = 0;
                $_procedimento_id = $row_procedimento['id'];
                $procedimento = $row_procedimento['nome'];

                ?>
                <tr class="old">
                    <td class="procedimentos" colspan="3" style="text-align: left" width="*">{{$procedimento}}</td>
                    <?php
                    $_relatorio = \App\Http\Helpers\Relatorios::getTotalProcedimentoMes($arena->id, $linha_cuidado->id, $_procedimento_id, $medico, $mes, $ano, $finalizacao);

                    for ($i = 1; $i <= $ultimo_dia_mes; $i++) {
                        $total = 0;
                        $dia = str_pad($i, 2, "0", STR_PAD_LEFT);

                        if (array_key_exists($dia, $_relatorio)) {
                            $total = !empty($_relatorio[$dia]) ? $_relatorio[$dia] : 0;

                        }

                        $total_procedimento_dia[$i][] = $total;
                        $total_procedimento += $total;

                        echo "<td class='text-center'>";
                        echo ($total > 0) ? "<strong>{$total}</strong>" : "<span style='color: #dcdcdc'>0</span>";
                        echo "</td>";
                    }
                    echo "<td class='text-center'><strong>{$total_procedimento}</strong></td>";
                    ?>
                </tr>
            @endforeach
            <tr class="footer-relatorio-detalhado">
                <td colspan="3"><strong>TOTAL</strong></td>
                @for($i = 1; $i <= $ultimo_dia_mes; $i++)
                    <?php
                    $total = array_sum($total_procedimento_dia[$i]);
                    $total_geral += $total;
                    unset($total_procedimento_dia[$i]);
                    ?>
                    <th><strong>{{$total}}</strong></th>
                @endfor
                <th><strong>{{$total_geral}}</strong></th>
            </tr>
        @endif
        <?php
        }
        }
        ?>
        </tbody>
    </table>
    <script>
        $(".exibicao_relatorio_data").change();
    </script>