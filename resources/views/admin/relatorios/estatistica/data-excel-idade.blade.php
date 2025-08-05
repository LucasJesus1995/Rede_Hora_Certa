<?php
$ultimo_dia_mes = date("t", mktime(0, 0, 0, $mes, '01', $ano));
$line = 5;

$colspan = ($ultimo_dia_mes + 4);
?>

@if(!empty($report))
    <html>
    <link media="all" type="text/css" rel="stylesheet" href="src/css/relatorio/cies.css">

    @if(!empty($report))

        <table width="100%" border="1">
            <tr>
                <th colspan="{!! $colspan-10 !!}" class="title">TABELA DE ATENDIMENTO (IDADE)</th>
                <td colspan="10" class="right"><b>{!! $arena->nome !!}</b> ({!! \App\Http\Helpers\Util::TipoRelatorio($tipo) !!})</td>
            </tr>
            <tr>
                <td colspan="{!! $colspan !!}" class="right">{!! \App\Http\Helpers\Util::getMesNome($mes) !!} / {!! $ano !!}</td>
            </tr>
            <tr>
                <td colspan="{!! $colspan !!}"></td>
            </tr>
            <tr>
                <th width="30">EXAMES</th>
                <th width="40">PROFISSIONAL</th>
                <th width="8">IDADE</th>
                @for($i = 1; $i <= $ultimo_dia_mes; $i++)
                    <th width="6">{!! $i !!}</th>
                @endfor
                <th width="7">TOTAL</th>
            </tr>

            @foreach($report AS $linha_cuidado => $profissionais)
                <?php $line_inicial = $line; ?>
                <?php $color = 0;?>
                @foreach($profissionais AS $profissional => $rows)
                    <?php $letter = "D";?>
                    @foreach(\App\Http\Helpers\Util::getListIntervalIdade() AS $key => $idade)
                        <tr class="line {!! $zebra =  ($color % 2 == "even") ? "odd" : "even" !!} ">
                            <td>{!! $linha_cuidado !!}</td>
                            <td>{!! $profissional !!}</td>
                            <td class="center degrade-zebra-{!! $key !!} " >{!! $idade !!}</td>
                            <?php $letter = "D";?>
                            @for($i = 1; $i <= $ultimo_dia_mes; $i++)
                                <td class="center degrade-zebra-{!! $key !!} ">
                                    @if(!empty($rows[$idade][$i])) {!!  $rows[$idade][$i] !!} @endif
                                </td>
                                <?php
                                if ($ultimo_dia_mes > $i) {
                                    $letter++;
                                }
                                ?>
                            @endfor
                            <td class="center sub-title degrade-zebra-{!! $key !!} ">=SUM(D{!! $line !!}:{!! $letter !!}{!! $line !!})</td>
                        </tr>
                        <?php $line++;?>
                    @endforeach
                    <?php $color++;?>
                @endforeach
                <tr>
                    <th colspan="3" class="sub-title">TOTAL</th>
                    <?php $letter = "D";?>
                    @for($i = 1; $i <= $ultimo_dia_mes; $i++)
                        <th class="center sub-title">=SUM({!! $letter !!}{!! $line_inicial !!}:{!! $letter !!}{!! ($line-1) !!})</th>
                        <?php $letter++;?>
                    @endfor
                    <th class="center sub-title">=SUM({!! $letter !!}{!! $line_inicial !!}:{!! $letter !!}{!! ($line-1) !!})</th>
                </tr>
                <tr>
                    <td colspan="{!! $colspan !!}"></td>
                </tr>
                <?php $line++;?>
                <?php $line++;?>
            @endforeach
        </table>
    @endif

    </html>
@endif

<?php //die;?>