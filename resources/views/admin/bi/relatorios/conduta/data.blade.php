@extends('pdf')
@section('content')
    <?php
    $medico = \App\Profissionais::find($profissional);
    $linha = \App\LinhaCuidado::find($linha_cuidado);
    $_arena = \App\Arenas::find($arena);

    $date = (array)json_decode($date);
    $data = App\Http\Helpers\Relatorios::RelatorioCondutaDataProfissionais($date, $arena, $linha_cuidado, $medico->id);

    $condutas = null;
    if (!empty($data)) {
        foreach ($data AS $row) {
            $condutas['principal'][$row->tipo_atendimento][$row->conduta_principal][] = $row->total;
            $condutas['secundaria'][$row->tipo_atendimento][$row->conduta_secundaria][] = $row->total;
        }
    }

    $mountheader = "<div class='text-align: right'>";

    if (!empty($_arena->nome))
        $mountheader .= "<strong>{$_arena->nome}</strong><br />";

    if (!empty($linha->nome))
        $mountheader .= $linha->nome . "<br />";

    if (!empty($medico->nome))
        $mountheader .= "<strong>" . strtoupper(\App\Http\Helpers\Util::String2DB($medico->nome)) . " (CRM:" . $medico->cro . ")<br /></strong>";

    $mountheader .= "{$date['inicial']} ~ {$date['final']}";

    $mountheader .= "</div>";
    ?>
    @include('elements.layout.pdf.header',['info'=>$mountheader])

    <div class="border-cinza">

        <hr class="margin10"/>
        <br/>

        <?php
        $_condutas = \App\Http\Helpers\AtendimentoHelpers::getCondutas();
        ?>
        @foreach(['principal' => 'CONDUTAS PRINCIPAL', 'secundaria' => 'CONDUTAS SECUNDÁRIA'] AS $k => $block)
            <?php
            $total = [];
            ?>
            <table class="table-relatorio">
                <tr>
                    <th colspan="4" style="border-bottom: 1px solid #000"><center>{!! $block !!}</center></th>
                </tr>
                <?php
                $procedimento_total = null;
                $i = 0;
                ?>
                @if(!empty($condutas[$k]))

                    @foreach($condutas[$k] AS $tipo_atendimento => $conduta)
                        <tr>
                            <th colspan="3"  style="background: #999">{!! $tipo_atendimento !!}</th>
                            <th   style="background: #999">
                                <center>Total</center>
                            </th>
                        </tr>
                        @foreach($conduta AS $k => $row)
                            <?php
                            $total[] = array_sum($row);
                            ?>
                            <tr style="background-color: <?php echo ($i % 2) ? "#EDEAEA" : ""; $i++;?>">
                                <td colspan="3">&nbsp;&nbsp;&nbsp;{!! array_key_exists($k, $_condutas) ? $_condutas[$k] : "#ND" !!}</td>
                                <td colspan="1">
                                    <center> {!! array_sum($row) !!}</center>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                    <tr>
                        <td colspan="3" class="no-border-bottom"></td>
                        <td class="center" style="background: #999" width="50"><b>{!! array_sum($total) !!}</b></td>
                    </tr>
                @endif
            </table>
            </br>
            </br>
        @endforeach

        <table class="table-relatorio">
            <tr>
                <td width="70%">
                    <div style="margin-top: 10px">Contagem realizada por:</div>
                </td>
                <td>
                    <div style="margin-top: 10px" class="center">Data: ______/ ______/ ____________</div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-top: 10px">Revisão contagem realizada por:</div>
                </td>
                <td>
                    <div style="margin-top: 10px" class="center">Data: ______/ ______/ ____________</div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-top: 10px">Atendimento SIGA realizado por:</div>
                </td>
                <td>
                    <div style="margin-top: 10px" class="center">Data: ______/ ______/ ____________</div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-top: 10px">Lançamento no BPA feito por:</div>
                </td>
                <td>
                    <div style="margin-top: 10px" class="center">Data: ______/ ______/ ____________</div>
                </td>
            </tr>

        </table>

    </div>

    @include('elements.layout.pdf.footer')

@stop
