<?php
    $agenda = $paciente;
    $paciente = (Object) \App\Pacientes::get($agenda->paciente);
    
    $arena = (Object) \App\Arenas::get($agenda->arena);
    $idade = null;

    if(!empty($paciente->nascimento)){
        $nascimento = explode("-",$paciente->nascimento);
        $idade = is_array($nascimento) ? \Carbon\Carbon::createFromDate($nascimento[0], $nascimento[1], $nascimento[2])->age : null;
    }
?>
<table width="100%" class="formulario" cellspacing="3">
    <tr>
        <th>
            <label>Nome: </label>
        </th>
        <td colspan="3">
            <span class="line">{{$paciente->nome}}</span>
        </td>
        <th>
             <label>Código: </label>
        </th>
        <td>
            <span class="line"> {{$paciente->cns}}</span>
        </td>
    </tr>
    <tr>
        <th>Mãe:</th>
        <td>{{$paciente->mae}}</td>
        <th>RG:</th>
        <td>{{$paciente->rg}}</td>
        <th>D. Nasc.:</th>
        <td>{{ !empty($paciente->nascimento) ? \App\Http\Helpers\Util::DB2User($paciente->nascimento) : null }} <small style="font-size: 9px;">({{$idade}} anos)</small></td>
    </tr>
    <tr>
        <th>Sexo:</th>
        <td>{{!empty($paciente->sexo) ?  \App\Http\Helpers\Util::Sexo($paciente->sexo) : null }}</td>
        <th>CNS:</th>
        <td>{{$paciente->cns}}</td>
        <th>Data:</th>
        <td>{{ !empty($paciente->created_at) ? \App\Http\Helpers\Util::DBTimestamp2UserDate($paciente->created_at) : null}} </td>
    </tr>
    @if(!empty($show_endereco) && $show_endereco)
        <tr>

            <th>Arena:</th>
            <td >{{ $arena->cnes }} - {{ $arena->nome }}</td>
            <th>Endereço:</th>
            <td colspan="3">
                {{$paciente->endereco}}, {{$paciente->numero}}, {{$paciente->cep}},
                @if(!empty($paciente->cidade))
                    <?php
                        $cidade = \App\Cidades::find($paciente->cidade);
                        if(!empty($cidade->id)){
                            $estado = \App\Estados::find($cidade->estado);

                            if(!empty($estado->id)){
                                echo "({$cidade->nome} - {$estado->nome})";
                            }
                        }
                    ?>
                @endif
            </td>
        </tr>
    @else
        <tr>
            <th>Arena:</th>
            <td colspan="5">{{ $arena->cnes }} - {{ $arena->nome }}</td>
        </tr>
    @endif
    <tr>
        <td width="70px" style="border: none"></td>
        <td width="230px" style="border: none"></td>
        <td width="70px" style="border: none"></td>
        <td width="200px" style="border: none"></td>
        <td width="100px" style="border: none"></td>
        <td width="130px" style="border: none"></td>
    </tr>
</table>