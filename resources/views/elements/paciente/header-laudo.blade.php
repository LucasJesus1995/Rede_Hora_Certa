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
<table style="font-size: 13px; margin-top: 30px;" width="100%" border="0" cellpadding="5" cellspacing="5">
    <tr>
        <td colspan="2">
            <span class="block font-bold">Nome</span>
            {{$paciente->nome}}
        </td>
        <td colspan="2">
            <span class="block font-bold">Mãe</span>
            {{$paciente->mae}}
        </td>
    </tr>
    <tr>
        <td>
            <span class="block font-bold">Data Nascimento</span>
            @if(!empty($paciente->nascimento))
                {{ \App\Http\Helpers\Util::DB2Users($paciente->nascimento)}} ({{$idade}} anos)
            @endif
        </td>
        <td>
            <span class="block font-bold">Sexo</span>
            @if(!empty($paciente->sexo))
                {{ \App\Http\Helpers\Util::Sexo($paciente->sexo)}}
            @endif

        </td>
        <td>
            <span class="block font-bold">R.G.</span>
            {{$paciente->rg}}
        </td>
    </tr>
    <tr>
        <td colspan="1">
            <span class="block font-bold">Arena</span>
            @if(!empty($arena))
                ({{$arena->cnes}}) - {{$arena->nome}}
            @endif
        </td>
        <td>
            @if($tipo == 'laudo')
                <span class="block font-bold">Data Exame</span>
                <?php
                echo    $data_exame = \App\Http\Helpers\Util::DBTimestamp2UserDate($atendimento->created_at);
                ?>

            @endif
        </td>
        <td colspan="2">
            <span class="block font-bold">SUS</span>
            {{$paciente->cns}}
        </td>
    </tr>
</table>
