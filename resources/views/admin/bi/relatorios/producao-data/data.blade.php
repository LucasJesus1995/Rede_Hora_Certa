@extends('pdf')
@section('content')
    <?php
    $medico = \App\Profissionais::find($profissional);
    $linha = \App\LinhaCuidado::find($linha_cuidado);
    $_arena = \App\Arenas::find($arena);

    $data = App\Http\Helpers\Relatorios::RelatorioProducao($date, $arena, $linha_cuidado, $medico->id, $digitador);
    $agendados = \App\Http\Helpers\Relatorios::QuantidadeDeAgendamentosSemLaudo($date, $arena, $linha_cuidado, $medico->id);

    $_date = json_decode($date);

    $mountheader = "<div class='text-align: right'>";

    if (!empty($_arena->nome))
        $mountheader .= "<strong>{$_arena->nome}</strong><br />";

    if (!empty($linha->nome))
        $mountheader .= $linha->nome . "<br />";

    if (!empty($medico->nome))
        $mountheader .= "<strong>" . strtoupper(\App\Http\Helpers\Util::String2DB($medico->nome)) . " (CRM:" . $medico->cro . ")<br /></strong>";

    $mountheader .= "{$_date->inicial} ~ {$_date->final}";

    $mountheader .= "</div>";
    ?>
    @include('elements.layout.pdf.header',['info'=>$mountheader])

    <div class="border-cinza">

        <br/>

        <table class="table-relatorio">
            <tr>
                <th colspan="3">Tipo de exame / Procedimento</th>
                <th>
                    <center>Total</center>
                </th>
            </tr>
            <?php
            $procedimento_total = null;

            $producao_sexo[1] = [];
            $producao_sexo[2] = [];

            $producao_idade[10] = [];
            $producao_idade[20] = [];
            $producao_idade[30] = [];
            $producao_idade[40] = [];
            $producao_idade[50] = [];
            $producao_idade[64] = [];
            $producao_idade[200] = [];

            $i = 0;

            $_notin_paciente = [];
            $total_exames = [];

            $_consultas = [];
            $total_consultas = [];
            ?>
            @if(!empty($data))
                @foreach($data AS $row)
                    <?php
                    $producao = App\Http\Helpers\Relatorios::RelatorioProducaoProfissional($arena, $linha_cuidado, $medico->id, $date, $row->id, $digitador);

                    if (in_array($row->id, [11, 12])) {
                        $_consultas[$row->id] = $row;
                        continue;
                    }

                    $countExames = 0;
                    if ($producao->contador_procedimento == 2) {
                        $procedimento_total[] = $producao->total;

                    }

                    $total_exames[] = $producao->total;
                    ?>
                    <tr style="background-color: <?php echo ($i % 2) ? "#EDEAEA" : ""; $i++;?>">
                        <td colspan="3">{{$row->nome}}</td>
                        <td colspan="1">
                            <center> {{$producao->total}}</center>
                        </td>
                    </tr>

                    <?php
                    $pacientes = App\Http\Helpers\Relatorios::RelatorioProducaoDetalhamentoPaciente2($date, $arena, $linha_cuidado, $medico->id, $row->id, $digitador);

                    foreach ($pacientes AS $paciente) {
                        if (!empty($paciente->sexo) && !in_array($paciente->id, $_notin_paciente))
                            $producao_sexo[$paciente->sexo][] = 1;

                        if (!empty($paciente->nascimento) && !in_array($paciente->id, $_notin_paciente)) {
                            $idade = \App\Http\Helpers\Util::Idade($paciente->nascimento);

                            switch ($idade) {
                                case $idade < 10 :
                                    $producao_idade[10][] = 1;
                                    break;
                                case $idade >= 10 && $idade < 20  :
                                    $producao_idade[20][] = 1;
                                    break;
                                case $idade >= 20 && $idade < 30  :
                                    $producao_idade[30][] = 1;
                                    break;
                                case $idade >= 30 && $idade < 40  :
                                    $producao_idade[40][] = 1;
                                    break;
                                case $idade >= 40 && $idade < 50  :
                                    $producao_idade[50][] = 1;
                                    break;
                                case $idade >= 50 && $idade < 65  :
                                    $producao_idade[64][] = 1;
                                    break;
                                case $idade >= 65 :
                                    $producao_idade[200][] = 1;
                                    break;
                            }
                        }

                        $_notin_paciente[$paciente->id] = $paciente->id;
                    }
                    ?>
                @endforeach
                <tr>
                    <td colspan="3" class="no-border-bottom"></td>
                    <td class="center" style="background: #999" width="50"><b>{!! array_sum($total_exames) !!}</b></td>
                </tr>
            @endif
        </table>
        <br/>
        @if(count($_consultas))
            <table class="table-relatorio ">
                <tr class="">
                    <th colspan="3">Consultas</th>
                    <th>
                        <center>Total</center>
                    </th>
                </tr>

                @foreach($_consultas AS $row)
                    <tr style="background-color: <?php echo ($i % 2) ? "#EDEAEA" : ""; $i++;?>">
                        <td colspan="3">{{$row->nome}}</td>
                        <td colspan="1" width="50">
                            <center> {{$row->total}}</center>
                        </td>
                    </tr>
                    <?php
                    $total_consultas[] = $row->total;
                    ?>
                @endforeach
                <tr>
                    <td colspan="3"></td>
                    <td class="center">{!! array_sum($total_consultas) !!}</td>
                </tr>
            </table>
            <br/>
        @endif
        <table class="table-relatorio">
            <tr>
                <th width="55%" class="sub-title">Consulta de Enfermagem ou Pacientes triados que não fizeram o exame</th>
                <td width="5%" class="sub-title ">&nbsp; {{$agendados}}</td>
                <th width="35%" class="sub-title">Total de exames realizados</th>
                <td width="5%" class="sub-title ">
                    <center>@if(!empty($total_exames)) {{ array_sum($total_exames)}} @endif</center>
                </td>
            </tr>
        </table>
        <br/>

        <table class="table-relatorio">
            <tr>
                <th width="50%" class="sub-title no-border">
                    <div style="padding-top: 14px">
                        <center>SEXO</center>
                    </div>
                </th>
                <th class="sub-title">
                    <table class="border-branca">
                        <tr>
                            <th width="45%" class="">Masculino</th>
                            <td width="5%" class="bg-white">
                                <center>{{array_sum($producao_sexo[1])}}</center>
                            </td>
                        </tr>
                        <tr>
                            <th>Feminino</th>
                            <td class="bg-white">
                                <center>{{array_sum($producao_sexo[2])}}</center>
                            </td>
                        </tr>
                    </table>
                </th>
            </tr>
        </table>

        <br/>

        <table class="table-relatorio">
            <tr>
                <th width="30%" class="sub-title">
                    <div style="margin-top: 35px">
                        <center>IDADE</center>
                    </div>
                </th>
                <th width="70%" class="sub-title ">
                    <table class="border-branca">
                        <tr >
                            <th width="40%">0~9 anos</th>
                            <td width="5%" class="bg-white">
                                <center>{{array_sum($producao_idade[10])}}</center>
                            </td>
                            <th width="40%">40~49 anos</th>
                            <td width="5%" class="bg-white">
                                <center>{{array_sum($producao_idade[50])}}</center>
                            </td>
                        </tr>
                        <tr>
                            <th>10~19 anos</th>
                            <td class="bg-white">
                                <center>{{array_sum($producao_idade[20])}}</center>
                            </td>
                            <th>50~64 anos</th>
                            <td class="bg-white">
                                <center>{{array_sum($producao_idade[64])}}</center>
                            </td>
                        </tr>
                        <tr>
                            <th>20~29 anos</th>
                            <td class="bg-white">
                                <center>{{array_sum($producao_idade[30])}}</center>
                            </td>
                            <th>65 anos ou +</th>
                            <td class="bg-white">
                                <center>{{array_sum($producao_idade[200])}}</center>
                            </td>
                        </tr>
                        <tr>
                            <th>30~39 anos</th>
                            <td class="bg-white">
                                <center>{{array_sum($producao_idade[40])}}</center>
                            </td>
                            <td colspan="2">&nbsp;</td>
                        </tr>
                    </table>
                </th>
            </tr>
        </table>

        <br/>


        </br>
        <table class="table-relatorio">
            <tr>
                <td width="70%">
                    <div style="margin-top: 10px">Contagem realizada por:</div>
                </td>
                <td>
                    <div style="margin-top: 10px" class="center">Data: ______/ ______/ ____________</div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-top: 10px">Revisão contagem realizada por:</div>
                </td>
                <td>
                    <div style="margin-top: 10px" class="center">Data: ______/ ______/ ____________</div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-top: 10px">Atendimento SIGA realizado por:</div>
                </td>
                <td>
                    <div style="margin-top: 10px" class="center">Data: ______/ ______/ ____________</div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-top: 10px">Lançamento no BPA feito por:</div>
                </td>
                <td>
                    <div style="margin-top: 10px" class="center">Data: ______/ ______/ ____________</div>
                </td>
            </tr>

        </table>

    </div>

    @include('elements.layout.pdf.footer')

@stop
