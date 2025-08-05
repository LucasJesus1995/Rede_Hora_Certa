<html>
<link media="all" type="text/css" rel="stylesheet" href="src/css/relatorio/diretoria.css">
<?php
$medico = \App\Profissionais::find($params['profissional']);
$linha = \App\LinhaCuidado::find($params['linha_cuidado']);
$_arena = \App\Arenas::find($params['arena']);
$pacientes = \App\Http\Helpers\Relatorios::RelatorioProducaoDetalhamentoPaciente3($params['date'], $params['arena'], $params['linha_cuidado'], $params['profissional'], $params['digitador']);

$_pacientes = [];
if ($pacientes) {
    foreach ($pacientes as $item) {
        $_pacientes[] = $item;
    }
}

$_date = json_decode($params['date']);
?>
@if(count($_pacientes))

    <table class="table table-striped table-responsive table-bordered  bg-light">
        <tr>
            <th width="8">#</th>
            <th width="15">Prontuário</th>
            <th width="25">SUS</th>
            <th width="40">Nome</th>
            <th width="10">Idade</th>
            <th width="12">Sexo</th>
            <th width="100">Exames</th>
            <th width="40">Digitador</th>
        </tr>

        <?php $i = 1;  ?>
        @foreach($_pacientes AS $row)
            <?php
            $procedimentos = \App\Procedimentos::getConsolidadosByAtendimento($row->atendimento_id);
            ?>
            <tr class="line {!! $zebra =  (@$zebra == "even") ? "odd" : "even" !!}">
                <td valign="top" align="center">{!! $i!!} </td>
                <td valign="top">{!! $row->prontuario !!}</td>
                <td valign="top" align="center">{!! strval($row->paciente_cns)!!}&nbsp;</td>
                <td valign="top">{!! $row->paciente_nome!!} </td>
                <td valign="top" align="center">
                    @if(!empty($row->paciente_nascimento))
                        {!!  \App\Http\Helpers\Util::Idade($row->paciente_nascimento) !!}
                    @endif
                </td>
                <td valign="top">
                    @if(!empty($row->paciente_sexo))
                        {!!  \App\Http\Helpers\Util::Sexo($row->paciente_sexo) !!}
                    @endif
                </td>
                <td height="{!! (15 * (count($procedimentos) ?: 1) ) !!}" valign="top">
                    @if(count($procedimentos))
                        @foreach($procedimentos AS $procedimento)
                            - {!! $procedimento->nome !!}<br/>
                        @endforeach
                    @endif
                </td>
                <td valign="top">
                    @if(!empty($row->digitador))
                        {!!  \App\Http\Helpers\Util::String2DB($row->digitador) !!}
                    @endif
                </td>
            </tr>
            <?php $i++;  ?>
        @endforeach
    </table>

@endif
</html>
