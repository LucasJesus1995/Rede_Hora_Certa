<?php
if(!isset($kit_white) || !$kit_white){
    $paciente = (Object)\App\Pacientes::get($agenda->paciente);
}else{
    $agenda = new stdClass();
    $paciente = new stdClass();
    $paciente->nome_social = null;
    $paciente->nome = "PACIENTE";

    $agenda->id = null;
    $agenda->data = date('Y-m-d');
    $agenda->linha_cuidado = $linha_cuidado->id;
}
?>

<h1 class="title">TERMO DE CONSENTIMENTO EM MAMOGRAFIA</h1>
<div style="text-align: right; margin-top: -10px">(Resolução CFM n. 1.931/2009 – Código de Ética Médica)</div>

<div class="font-contrato-size" style="margin-top: 20px">
    <p>
        Eu,_____________________________________________________________, RG nº: ______________________ e CPF nº: ______________________, portadora de implante e/ou prótese mamária, AUTORIZO a realização do exame de mamografia solicitada por meu/minha MÉDICO (A) ASSISTENTE, por entender que a mamografia é um exame seguro e essencial à detecção e diagnóstico do câncer de mama. Fui informada que um exame de mamografia bem realizado requer a compressão da mama e a realização de manobras que visam o afastamento dos implantes para melhor visualização do tecido mamário e eventuais lesões. Que estas manobras são controladas e estão em conformidade com normas internacionais e serão registradas durante todo o exame para documentação. Embora milhares de mulheres usando próteses e implantes tenham feito mamografia sem qualquer problema em todo o mundo, há raros relatos de complicações ocorridas após uma mamografia, como roturas, vazamentos e deslocamentos. Que esse risco, no entanto, é muitíssimo menor que os benefícios trazidos pela realização da mamografia.
        <br />Estou ciente que algumas complicações, como rotação ou ruptura nos implantes mamários, podem ocorrer mesmo com o uso de técnica adequada, sendo possível que ocorram mesmo durante atividades diárias e não sejam percebidas. Próteses colocadas há muitos anos tendem a ser mais vulneráveis. Também não é raro que uma complicação pré-existente seja detectada pela mamografia (a mamografia inclusive às vezes é usada com essa finalidade).
        <br />Estou ciente de que muitas das complicações mencionadas não trazem problemas significativos à saúde da mulher, mas em situações raras podem precisar de correção através de cirurgia.
        <br />Dessa forma, afirmo que fui devidamente esclarecida sobre todas as informações acima expostas e as compreendi, declarando estar ciente a respeito do risco mínimo de dano a mim e à minha prótese e/ou implante com a realização da mamografia. Declaro também que todas as minhas dúvidas foram devidamente esclarecidas e assim sendo autorizo a sua realização.
    </p>

    <div style="margin-top: 20px">
        @include('elements.layout.kit.aux.lei-lgpd')
    </div>

    <div style="margin-top: 5px; text-align: right">
        {!! \App\Http\Helpers\Util::dateExtensoCidade($agenda->data, "São Paulo"); !!}

        <br/><br/><br/>
        @include('elements.layout.kit.assinaturas.paciente_e_responsavel')
    </div>

    <div style="position: absolute; bottom: -50px">
        @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id])
    </div>
</div>
