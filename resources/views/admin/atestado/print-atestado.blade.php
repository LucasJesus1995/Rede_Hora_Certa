@extends('pdf')

@section('content')

    <title>atestado-medico</title>

    <div style=" padding: 15px;" class="bg-white">

        <table class="no-border">
            <tr>
                <td class="no-border center">
                    <img src="src/image/dr_saude.png" class="img-responsive"
                         style="width: 200px; margin-left: 0px; margin-top: 0px"/>
                </td>
            </tr>
        </table>

        <table style="margin-top: 30px;">
            <tr>
                <td class="no-border width-50"><b>PACIENTE:</b> <p><?php echo $atestado[0]['pacienteNome'] ?></p></td>
                <td class="no-border"><b>MÃE:</b> <p><?php echo $atestado[0]['pacienteMae'] ?></p></td>
            </tr>
        </table>
        <table class="no-border">
            <tr>
                <td class="no-border"><b>CPF:</b> <p><?php echo $atestado[0]['cpf'] ?></p></td>
                <td class="no-border"><b>DATA NASCIMENTO:</b> <p><?php echo $atestado[0]['pacienteNascimento'] ?></p></td>
                <td class="no-border"><b>DATA:</b> <p><?php echo $atestado[0]['data'] ?></p></td>
                <td class="no-border"><b>ATENDIMENTO:</b> <p><?php echo $atestado[0]['atendimento'] ?></p></td>
            </tr>
        </table>

        <table>
            <tr>
                <td class="no-border width-50"><b>UNIDADE:</b> <p><?php echo $atestado[0]['arenaNome'] ?></p></td>
                <td class="no-border"><b>ESPECIALIDADE:</b> <p><?php echo $atestado[0]['nomeLinhaCuidado'] ?></p></td>
            </tr>
        </table>

        <hr>

        <div style="margin-top: 150px;">
            <div style="font-size: 16px !important; font-weight: bold; text-align: center;">ATESTADO MÉDICO</div>
        </div>

        <div style="margin-top: 80px;">
            <div class="font-size-13 center">Atesto para Empresa <?php echo $atestado[0]['empresa'] ?> que <?php echo $pronome_tratamento ?> <?php echo $atestado[0]['pacienteNome'] ?>,</div>
            <div class="font-size-13 center">portador do CPF <?php echo $atestado[0]['cpf'] ?>, esteve sob meus cuidados médicos</div>
            <div class="font-size-13 center">no dia <?php echo $atestado[0]['data'] ?> das <?php echo $atestado[0]['hora_chegada'] ?> as <?php echo $atestado[0]['hora_saida'] ?> horas.</div>
            <br>
            <div class="font-size-13 center">Foi orientado a retornar ao trabalho.</div>
        </div>

        <div style="margin-top: 80px;">
            <div class="font-size-13 center">CID: <?php echo $atestado[0]['codigoCid']?> - <?php echo $atestado[0]['descricaoCid'] ?></div>
        </div>

        <div style="margin-top: 100px; margin-left: 36%;">
            <hr style="width: 200px;">
        </div>

        <div class="font-size-13 center"><?php echo $atestado[0]['nomeMedico'] ?></div>
        <div class="font-size-13 center">CRM <?php echo $atestado[0]['crmMedico'] ?></div>

        <div class="footer" style="bottom: 10px" >
            <hr>
            <br>
            <table class="no-border">
                <tr>
                    <td style="text-align: left;" class="no-border">
                        <img src="src/image/logo/prefeitura.png" class="img-responsive"
                             style="width: 150px; margin-top: 0px"/>
                    </td>
                    <td style="" class="no-border">
                        <img src="src/image/logo/cies.png" class="img-responsive"
                             style="width: 50px; margin-top: 0px"/>
                    </td>
                    <td style="text-align: right;" class="no-border">
                        <img src="src/image/logo/sus.png" class="img-responsive"
                             style="width: 100px; margin-top: 0px"/>
                    </td>
                </tr>
            </table>
        </div>

    </div>
@stop