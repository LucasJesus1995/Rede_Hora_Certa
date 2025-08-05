<html>
    <link media="all" type="text/css" rel="stylesheet" href="src/css/relatorio/biopsia.css">

    <table>
        <tr>
            <th colspan="5">AGENDA</th>
            <th colspan="6">ATENDIMENTO</th>
            <th colspan="6">PACIENTE</th>
        </tr>
        <tr>
            <th>CODIGO</th>
            <th>AGENDAMENTO</th>
            <th>UNIDADE</th>
            <th>ESPECIALIDADE</th>
            <th>UBS</th>

            <th>CODIGO</th>
            <th>ABERTURA</th>
            <th>RECEPCAO (IN)</th>
            <th>RECEPCAO (OUT)</th>
            <th>MEDICO (IN)</th>
            <th>MEDICO (OUT)</th>

            <th>NOME</th>
            <th>CPF</th>
            <th>SEXO</th>
            <th>DT. NASCIMENTO</th>
            <th>CELULAR</th>
            <th>CIDADE</th>

        </tr>
        @if(!empty($relatorio))
            @foreach($relatorio AS $key => $row)
                <?php
                    $cidade = !empty($row->cidade) ? \App\Cidades::get($row->cidade) : null;
                ?>
                <tr class="line {!! ($key % 2) ? 'odd' : 'even' !!}">

                    <td>{!! $row->agendas_id !!}</td>
                    <td>{!! $row->agendas_data !!}</td>
                    <td>{!! $row->arena_nome !!}</td>
                    <td>{!! $row->linha_cuidado_nome !!}</td>
                    <td>{!! $row->estabelecimento !!}</td>

                    <td>{!! $row->atendimento_id !!}</td>
                    <td>{!! $row->atendimento_criacao !!}</td>
                    <td>{!! $row->recepcao_in !!}</td>
                    <td>{!! $row->recepcao_out !!}</td>
                    <td>{!! $row->medico_in !!}</td>
                    <td>{!! $row->medico_out !!}</td>

                    <td>{!! $row->nome !!}</td>
                    <td>{!! \App\Http\Helpers\Mask::Cpf($row->cpf) !!}</td>
                    <td>{!! !empty($row->sexo) ? \App\Http\Helpers\Util::Sexo($row->sexo) : null !!}</td>
                    <td>{!! $row->nascimento !!}</td>
                    <td>{!! \App\Http\Helpers\Mask::telefone($row->celular) !!}</td>
                    <td>{!! !empty($cidade) ? $cidade->nome : null !!}</td>

                </tr>
            @endforeach
        @endif
    </table>

</html>