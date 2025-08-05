@if(!empty($relatorio))
    <html>
    <link media="all" type="text/css" rel="stylesheet" href="src/css/relatorio/diretoria.css">

    @if(!empty($relatorio))
        <?php
        $mes = \App\Http\Helpers\Util::getMesNome($params['mes']);
        $ln = 2;

        $tipo_atendimento = \App\Http\Helpers\Util::getTipoAtendimento();
        ?>
        <table class="table table-striped table-responsive table-bordered  bg-light">
            <tr>
                <th width="20">Atendimento</th>
                <th width="15">Mês</th>
                <th width="9">Dia</th>
                <th width="60">Unidade</th>
                <th width="30">Tipo Atendimento</th>
                <th width="40">Especialidade</th>
                <th width="20">Cód. Médico</th>
                <th width="60">Médico</th>
                <th width="20">Cód. Procedimento</th>
                <th width="90">Procedimento</th>
                <th width="17">Quantidade</th>
                <th width="20">Qtd. (Multiplicada)</th>
            </tr>

            @foreach($relatorio AS $row)
                <?php
                    $_tipo_atendimento = array_key_exists($row->tipo_atendimento, $tipo_atendimento) ? $tipo_atendimento[$row->tipo_atendimento] : null;
                ?>
                <tr class="line {!! $zebra =  (@$zebra == "even") ? "odd" : "even" !!}">
                    <td class="center">{!! $row->codigo_atendimento !!}</td>
                    <td>{!! $mes !!}</td>
                    <td class="center">{!! intval($row->dia) !!}</td>
                    <td>{!! $row->arena !!}</td>
                    <td>{!! $_tipo_atendimento !!}</td>
                    <td>{!! $row->linha_cuidado !!}</td>
                    <td class="center">{!! $row->codigo_medico !!}</td>
                    <td>{!! $row->medico !!}</td>
                    <td class="center">{!! \App\Http\Helpers\Mask::CodigoProcedimento($row->codigo_procedimento) !!}</td>
                    <td>{!! $row->procedimento !!}</td>
                    <td class="center">{!! intval($row->quantidade) !!}</td>
                    <td class="center">=(K{!! $ln !!}*{!!  \App\Http\Rules\Faturamento\Procedimentos::getMultiplicadorMedicos($row->procedimento_id) !!})</td>
                </tr>
                <?php $ln++;?>
            @endforeach
        </table>
    @endif
    </html>
@endif