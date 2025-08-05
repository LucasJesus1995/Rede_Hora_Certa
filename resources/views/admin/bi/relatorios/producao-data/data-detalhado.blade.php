@extends('pdf')
@section('content')

<?php
    $medico = \App\Profissionais::find($profissional);
    $linha  = \App\LinhaCuidado::find($linha_cuidado);
    $_arena  = \App\Arenas::find($arena);
    $pacientes = \App\Http\Helpers\Relatorios::RelatorioProducaoDetalhamentoPaciente($date, $arena, $linha_cuidado, $profissional, $digitador);

    $_pacientes = [];
    if($pacientes){
        foreach ($pacientes as $item) {
            $_pacientes[$item->procedimento_nome][] = $item;
        }
    }

    $_date = json_decode($date);

    $mountheader = "<div class='text-align: right'>";

    if(!empty($_arena->nome))
        $mountheader .= "<strong>{$_arena->nome}</strong><br />";

    if(!empty($linha->nome))
        $mountheader .= $linha->nome."<br />";

    if(!empty($medico->nome))
        $mountheader .= "<strong>".strtoupper(\App\Http\Helpers\Util::String2DB($medico->nome)) ."(CRM:". $medico->cro .")<br /></strong>";

    $mountheader .= "{$_date->inicial} ~ {$_date->final}";

    $mountheader .= "</div>";

    $count_page = 0;
?>
@include('elements.layout.pdf.header',['info'=>$mountheader])

<div style="margin: 0px;">

     <br />

    @foreach($_pacientes AS $key => $pacientes)
        <div class=""><b>{!! $key !!}</b></div>
            <table class="table-relatorio">
                <tr>
                    <th class="font-small" width="4%">#</th>
                    <th class="font-small" width="25%">Nome</th>
                    <th class="font-small" width="30%">Exame</th>
                    <th class="font-small" width="10%">SUS</th>
                    <th class="font-small" width="4%">Idade</th>
                    <th class="font-small" width="5%">Sexo</th>
                    <th class="font-small" width="22%">Digitador</th>
                </tr>
                <?php $i = 0;  ?>

                @foreach($pacientes AS $row)
                    <tr style="background-color: <?php echo ($i % 2) ? "#EDEAEA"  : "" ; $i++;?>">
                        <td class="font-small">{{$i}}</td>
                        <td class="font-small">
                            {{$row->paciente_nome}}<br />
                            <strong class="font-small">{!! $row->prontuario !!}</strong>
                        </td>
                        <td>
                            <?php
                                $procedimentos = \App\Procedimentos::getConsolidadosByAtendimento($row->atendimento_id);
                            ?>
                            @if(count($procedimentos))
                                <ul>
                                    @foreach($procedimentos AS $procedimento)
                                        <li class="font-small">- {!! $procedimento->nome !!}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </td>
                        <td class="font-small">{{$row->paciente_cns}}</td>
                        <td class="font-small">
                            @if(!empty($row->paciente_nascimento))
                                {{ \App\Http\Helpers\Util::Idade($row->paciente_nascimento) }}
                            @endif
                        </td>
                        <td class="font-small">
                            @if(!empty($row->paciente_sexo))
                                {{ \App\Http\Helpers\Util::Sexo($row->paciente_sexo) }}
                            @endif
                        </td>
                        <td class="font-small">
                            @if(!empty($row->digitador))
                                {{ \App\Http\Helpers\Util::String2DB($row->digitador) }}
                            @endif
                        </td>
                    </tr>

                @endforeach
            </table>
            <br /><br />
        </div>
        <?php $count_page++;?>
        @if($count_page != count($_pacientes))
            <div style="page-break-before: always;"></div>
        @endif
    @endforeach
</div>

@include('elements.layout.pdf.footer')

@stop