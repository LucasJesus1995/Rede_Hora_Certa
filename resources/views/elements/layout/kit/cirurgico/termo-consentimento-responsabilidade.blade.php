<?php
$agenda = new stdClass();
$agenda->id = null;
$agenda->data = date('Y-m-d');
?>

<table>
    <tr>
        <td>&nbsp;</td>
        <td class="logo" valign="center" nowrap style="text-align: right" width="40px">
            <img style='height: 60px; margin-bottom: 10px' src='src/image/logo/cies.png'>
        </td>
    </tr>
</table>

<h1 class="title">TERMO DE RESPONSABILIDADE E CONSENTIMENTO</h1>

<div class="font-contrato-size" style="line-height: 30px !important; font-size: 16px !important; text-align: justify !important;">
    Pelo presente TERMO DE RESPONSABILIDADE E CONSENTIMENTO, Eu, _____________________ ___________________________________________________________________________________, portador(a) do RG sob o n&deg; ______________, inscrito(a) no CPF/MF sob o n&deg;
    ____________________,
    registrado(a) no Cartão Nacional do Sistema Único de Saúde/SUS sob o n&deg; ______________________ _______________________________________, residente e domiciliado(a) à ___________________
    ___________________________________________________________, de número ___________ para todos os fins e efeitos,
    SOLICITO E AUTORIZO o envio do resultado do(s) meu(s) exame(s) que poderá(ão) ou não ser acompanhado(s) de imagens e demais documentos, relativo ao meu prontuário médico de n&deg; _________________, na forma de endereço eletrônico a seguir pelo
    e-mail ___________________________________________________________________________________

    <br/>
    <br/>
    <br/>
    <br/>

    Fica aqui registrado, que ME RESPONSABILIZO pelo acesso ao sítio eletrônico informado acima, e que este TERMO DE RESPONSABILIDADE E CONSENTIMENTO é expressão da verdade e por ele respondo integralmente.


    <p style="margin-top: 50px;  line-height: 30px !important; font-size: 16px !important; text-align: right !important;">
        {!! \App\Http\Helpers\Util::dateExtensoCidade($agenda->data, "São Paulo"); !!}
    </p>

    <table width="100%" style="margin-top: 140px">
        <tr>
            <td width="50%" style="text-align: center !important;">______________________________________________________<br />Paciente (ou Responsável)</td>
            <td width="50%" style="text-align: center">&nbsp;</td>
        </tr>
    </table>
</div>
<div style="position: absolute; bottom: -50px">
    @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id])
</div>
</div>