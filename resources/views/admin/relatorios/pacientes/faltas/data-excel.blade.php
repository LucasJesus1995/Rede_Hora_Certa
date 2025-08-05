@if(!empty($relatorio))
    <html>
    <link media="all" type="text/css" rel="stylesheet" href="src/css/relatorio/diretoria.css">

        <table class="table table-striped table-responsive table-bordered  bg-light">
            <tr>
                <th width="10">Agenda</th>
                <th width="20">Data</th>
                <th width="50">Unidade</th>
                <th width="50">Especialidade</th>
                <th width="35">Procedimentos</th>
                <th width="40">Nome Paciente</th>
                <th width="22">SUS</th>
                <th width="25">Celular</th>
                <th width="25">Telefone (Comercial)</th>
                <th width="25">Telefone (Residencial)</th>
                <th width="25">Telefone (Contato)</th>
                <th width="35">Email</th>
            </tr>

            @foreach($relatorio AS $row)
                <?php
                $row = (Array) $row;

                $_classificacao = App\Http\Helpers\Util::getTipoAtendimento();
                ?>
                <tr class="line {!! $zebra =  (@$zebra == "even") ? "odd" : "even" !!}">
                    <td align="center">{!! $row['id'] !!}</td>
{{--                    <td  align="center">{!! \App\Http\Helpers\Util::DBTimestamp2User2($row['data']) !!}</td>--}}
                    <td  align="center">{!! $row['data'] !!}</td>
                    <td>{!! $row['arena'] !!}</td>
                    <td>{!! $row['especialidade'] !!}</td>
{{--                    <td>{!! !empty($row['tipo_atendimento']) && array_key_exists($row['tipo_atendimento'], $_classificacao) ? $_classificacao[$row['tipo_atendimento']] : null !!}</td>--}}
                    <td></td>
                    <td>{!! $row['nome'] !!}</td>
                    <td  align="center">{!! $row['cns'] !!}&nbsp;</td>
                    <td align="right">{!! $row['celular'] !!}</td>
                    <td align="right">{!! $row['telefone_comercial'] !!}</td>
                    <td align="right">{!! $row['telefone_residencial'] !!}</td>
                    <td align="right">{!! $row['telefone_contato'] !!}</td>
                    <td>{!! $row['email'] !!}</td>
                </tr>
            @endforeach
        </table>
    </html>
@endif