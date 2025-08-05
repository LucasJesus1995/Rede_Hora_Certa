<?php
    $helper = new \App\Http\Helpers\Util();
?>
<html>
    <link media="all" type="text/css" rel="stylesheet" href="src/css/relatorio/biopsia.css">

    <table>
        <tr>
            <th colspan="14">PACIENTES</th>
            <th colspan="5">AGENDA</th>
            <th colspan="5">ATENDIMENTO</th>
        </tr>
        <tr>
            <th>NOME</th>
            <th>CNS</th>
            <th>CPF</th>
            <th>RG</th>
            <th>NASCIMENTO</th>
            <th>SEXO</th>
            <th>ESTADO CIVIL</th>
            <th>RAÇA COR</th>
            <th>CEP</th>
            <th>ENDEREÇO</th>
            <th>NUMERO</th>
            <th>BAIRRO</th>
            <th>ENDEREÇO TIPO</th>
            <th>CRIAÇÃO</th>

            <th>CÓDIGO</th>
            <th>DATA</th>
            <th>UNIDADE</th>
            <th>ESPECIALIDADE</th>
            <th>PROCEDIMENTO</th>

            <th>CÓDIGO</th>
            <th>CRIAÇÃO</th>
            <th>PREFERENCIAL</th>
            <th>SALA</th>
            <th>MÉDICO</th>

        </tr>
        @if(!empty($data))
            @foreach($data AS $key => $row)
                <?php
                    $estado_civil =  (!empty($row->estado_civil) && is_int($row->estado_civil)) ? $helper->EstadoCivil($row->estado_civil) : null;
                    $sexo =  (!empty($row->sexo) && is_int($row->sexo)) ? $helper->Sexo($row->sexo) : null;

                    if($row->raca_cor == 99){
                        $row->raca_cor = "099";
                    }

                    $raca_cor =  (!empty($row->raca_cor) && is_int($row->raca_cor)) ? $helper->RacaCor() : null;
                    $nascimento = $helper->DB2User($row->nascimento);
                    $endereco_tipo = $helper->EnderecoTipo($helper->StrPadLeft($row->endereco_tipo, 3));
                    $preferencial = $helper->Ativo($row->atendimento_preferencial);

                    $cpf = \App\Http\Helpers\Mask::Cpf($row->cpf);
                    $cep = \App\Http\Helpers\Mask::Cep($row->cep);

                    $paciente_criacao = $helper->DBTimestamp2User2($row->created_at);
                    $agenda_data = $helper->DBTimestamp2User2($row->data);
                    $atendimento_data = $helper->DBTimestamp2User2($row->atendimento_data);

                    $sala = (!empty($row->sala) && is_int($row->sala)) ? $helper->Sala($row->sala) : null;

                    $sala = (!is_array($sala)) ? $sala : null;
                    $preferencial = (!is_array($preferencial)) ? $preferencial : null;
                    $endereco_tipo = (!is_array($endereco_tipo)) ? $endereco_tipo : null;
                    $raca_cor = (!is_array($raca_cor)) ? $raca_cor : null;
                ?>
                <tr class="line {!! ($key % 2) ? 'odd' : 'even' !!}">

                    <td>{!! $row->nome !!}</td>
                    <td>{!! $row->cns !!}</td>
                    <td>{!! $cpf !!}</td>
                    <td>{!! $row->rg !!}</td>
                    <td>{!! $nascimento !!}</td>
                    <td>{!! $sexo !!}</td>
                    <td>{!! $estado_civil !!}</td>
                    <td>{!! $raca_cor !!}</td>
                    <td>{!! $cep !!}</td>
                    <td>{!! $row->endereco !!}</td>
                    <td>{!! $row->numero !!}</td>
                    <td>{!! $row->bairro !!}</td>
                    <td>{!! $endereco_tipo !!}</td>
                    <td>{!! $paciente_criacao !!}</td>

                    <td>{!! $row->id !!}</td>
                    <td>{!! $agenda_data !!}</td>
                    <td>{!! $row->arena !!}</td>
                    <td>{!! $row->linha_cuidado !!}</td>
                    <td>{!! $row->procedimento !!}</td>

                    <td>{!! $row->atendimento_id !!}</td>
                    <td>{!! $atendimento_data !!}</td>
                    <td>{!! $preferencial !!}</td>
                    <td>{!! $sala !!}</td>
                    <td>{!! $row->medico !!}</td>

                </tr>
            @endforeach
        @endif
    </table>

</html>