<?php
    $paciente = (Object) \App\Pacientes::get($paciente);

    $nascimento = !empty($paciente->nascimento) ?  explode("-",$paciente->nascimento) : null;
    if($nascimento)
        $idade = is_array($nascimento) ? \Carbon\Carbon::createFromDate($nascimento[0], $nascimento[1], $nascimento[2])->age : null;

   $tipo = !empty($tipo) ? $tipo : null;

?>
<ul class="list-group md-whiteframe-z0">
    @if($tipo == 'laudo')
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm-3">
                    <span class="block font-bold">Agendamento</span>
                    {{$agenda['id']}}
                </div>
                <div class="col-sm-3">
                    <span class="block font-bold">Data</span>
                    {{ \App\Http\Helpers\Util::DBTimestamp2User($agenda['data']) }}
                </div>
            </div>
        </li>
    @endif

    <li class="list-group-item">
        <div class="row">
            <div class="col-sm-5">
                <span class="block font-bold">Nome</span>
                {!! \App\Pacientes::nomeSocialLayout($paciente->nome, $paciente->nome_social) !!}
            </div>
            <div class="col-sm-4">
                <span class="block font-bold">Mãe</span>
                {{$paciente->mae}}
            </div>
            <div class="col-sm-3">
                <span class="block font-bold">D.N.</span>
                @if($nascimento)
                    {{\Carbon\Carbon::createFromFormat('Y-m-d', $paciente->nascimento)->toFormattedDateString()}} ({{$idade}} anos)
                @endif
            </div>
        </div>
    </li>
    <li class="list-group-item">
        <div class="row">
            <div class="col-sm-3">
                <span class="block font-bold">SUS</span>
                  {{$paciente->cns}}
            </div>
            <div class="col-sm-3">
                <span class="block font-bold">Sexo</span>
                @if(!empty($paciente->sexo))
                    {{ \App\Http\Helpers\Util::Sexo($paciente->sexo)}}
                @endif
            </div>
            <div class="col-sm-3">
                <span class="block font-bold">R.G.</span>
                {{$paciente->rg}}
            </div>
            @if(isset($chegada))
            <div class="col-sm-3">
                <span class="block font-bold">Data</span>
                {{ \App\Http\Helpers\Util::DBTimestamp2User($chegada)}}
            </div>
            @endif
            @if(isset($arena))
            <div class="col-sm-3">
                <strong>{{$arena->nome}}</strong><br />
                {{$arena->cnes}}
            </div>
            @endif
        </div>
    </li>
</ul>