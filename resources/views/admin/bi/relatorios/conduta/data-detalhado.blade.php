@extends('pdf')
@section('content')

    <?php
    $medico = \App\Profissionais::find($profissional);
    $linha = \App\LinhaCuidado::find($linha_cuidado);
    $_arena = \App\Arenas::find($arena);

    $date = (array)json_decode($date);
    $data = \App\Http\Helpers\Relatorios::RelatorioCondutaDataProfissionais($date, $arena, $linha_cuidado, $profissional);
    $condutas = null;
    if (!empty($data)) {
        foreach ($data AS $row) {
            $condutas[$row->tipo_atendimento][$row->conduta_principal][] = $row;
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

    $count_page = 0;

    $_condutas = \App\Http\Helpers\AtendimentoHelpers::getCondutas();
    $_sexo = \App\Http\Helpers\Util::Sexo();
    ?>
    @include('elements.layout.pdf.header',['info'=>$mountheader])

    @if(!empty($condutas))
        <div style="margin: 0px;">
            <br/>

            @foreach($condutas AS $tipo_atendimento => $conduta)
                <div class="bloco" style="margin-bottom: 10px;">
                    <h2 style="margin-bottom: 10px; background-color: #999 !important" > {!! $tipo_atendimento !!}</h2>

                    @foreach($conduta AS $k => $rows)
                        <div class=""><b>&nbsp;{!! array_key_exists($k, $_condutas) ? $_condutas[$k] : null !!}</b></div>
                        <table class="table-relatorio" style="margin: 0 3px 0 1px">
                            <tr>
                                <th class="font-small" width="2%">#</th>
                                <th class="font-small" width="5%">Prontuário</th>
                                <th class="font-small" width="24%">Nome</th>
                                <th class="font-small" width="6%">SUS</th>
                                <th class="font-small" width="8%">Conduta Secundária</th>
                                <th class="font-small" width="5%">Total</th>
                            </tr>
                            @foreach($rows AS $i => $row)
                                <tr style="background-color: <?php echo ($i % 2) ? "#EDEAEA" : ""; $i++;?>">
                                    <td class="font-small">{!! $i !!}</td>
                                    <td class="font-small">{!! $row->prontuario !!}</td>
                                    <td class="font-small">{!! $row->nome !!}</td>
                                    <td class="font-small">{!! $row->cns !!}</td>
                                    <td class="font-small">{!! array_key_exists($row->conduta_secundaria, $_condutas) ? $_condutas[$row->conduta_secundaria] : null !!}</td>
                                    <td class="font-small">{!! $row->total !!}</td>
                                </tr>
                            @endforeach
                        </table>
                        <br/>
                    @endforeach
                </div>
            @endforeach
        </div>
        <?php $count_page++;?>

        </div>
    @else
        <br/>Nenhum registro encontrado!
    @endif
    @include('elements.layout.pdf.footer')

@stop