@if(!empty($relatorio))
    <html>
    <link media="all" type="text/css" rel="stylesheet" href="src/css/relatorio/diretoria.css">
    <?php
        $tipo_atendimento = \App\Http\Helpers\Util::getTipoAtendimento();
    ?>
    @if(!empty($relatorio))
        <table class="table table-striped table-responsive table-bordered  bg-light">
            <tr>
                <th width="80">Unidade</th>
                <th width="60">Especialidade</th>
                <th width="27">Procedimento</th>
                <th width="15">Data</th>
                <th width="35">Total (Pacientes atendidos)</th>
                <th width="40">Total (Consolidados pacientes atendidos)</th>
            </tr>

            @foreach($relatorio AS $row)
                <tr class="line {!! $zebra =  (@$zebra == "even") ? "odd" : "even" !!}">
                    <td>{!! $row->arena !!}</td>
                    <td>{!! $row->linha_cuidado !!}</td>
                    <td>{!! array_key_exists($row->tipo_atendimento, $tipo_atendimento) ? $tipo_atendimento[$row->tipo_atendimento] : null !!}</td>
                    <td align="center">{!! $row->data !!}</td>
                    <td>{!! $row->total !!}</td>
                    <td>{!! $row->total_consolidado !!}</td>
                </tr>
            @endforeach
        </table>
    @endif
    </html>
@endif