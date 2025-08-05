<?php
    $atendimento = \App\Atendimentos::where('agenda','=',$agenda->id)->get()->first();

    $agenda = $paciente;
    $paciente = (Object) \App\Pacientes::get($agenda->paciente);

    $arena = (Object) \App\Arenas::get($agenda->arena);

    if(!empty($paciente->nascimento)){
        $nascimento = explode("-",$paciente->nascimento);
        $idade = is_array($nascimento) ? \Carbon\Carbon::createFromDate($nascimento[0], $nascimento[1], $nascimento[2])->age : null;
    }
?>
<table style="font-size: 13px;" width="100%" border="0" cellpadding="20" cellspacing="10">
    <tr>
        <td colspan="2" class="no-border">
            <b>NOME</b><br />
            {!! \App\Pacientes::nomeSocialLayout($paciente->nome, $paciente->nome_social) !!}
        </td>
        <td colspan="2" class="no-border">
            <b>MÃE</b><br />
            {{$paciente->mae}}
        </td>
    </tr>
    <tr>
        <td class="no-border">
            <b>DATA NASCIMENTO</b><br />
            @if(!empty($paciente->nascimento))
                {{ \App\Http\Helpers\Util::DB2Users($paciente->nascimento)}} ({{$idade}} anos)
            @endif
        </td>
        <td class="no-border">
            <b>SEXO</b><br />
            @if(!empty($paciente->sexo))
                {{ \App\Http\Helpers\Util::Sexo($paciente->sexo)}}
            @endif

        </td>
        <td class="no-border">
            <b>RG.</b><br />
            {{ \App\Http\Helpers\Mask::RG($paciente->rg)}}
        </td>
    </tr>
    <tr>
        <td colspan="1" class="no-border">
            <b>UNIDADE</b><br />
            @if(!empty($arena))
                {{$arena->nome}}
            @endif
        </td>
        <td class="no-border">
            @if($tipo == 'laudo')
                <b>DATA EXAME</b><br />
                <?php
                    echo $data_exame = \App\Http\Helpers\Util::DBTimestamp2UserDate($atendimento->created_at);
                ?>
            @endif
        </td>
        <td colspan="2" class="no-border">
            <b>SUS</b><br />
            {{ \App\Http\Helpers\Mask::SUS($paciente->cns)}}
        </td>
    </tr>
    <tr>
        <td width="45%" class="no-border"></td>
        <td width="15%" class="no-border"></td>
        <td width="15%" class="no-border"></td>
        <td width="25%" class="no-border"></td>
    </tr>
</table>