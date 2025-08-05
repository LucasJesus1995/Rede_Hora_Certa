<?php
if (!isset($kit_white) || !$kit_white) {
    $paciente = (Object)\App\Pacientes::get($agenda->paciente);
} else {
    $agenda = new stdClass();
    $paciente = new stdClass();
    $paciente->nome_social = null;
    $paciente->nome = "PACIENTE";

    $agenda->id = null;
    $agenda->data = date('Y-m-d');
    $agenda->linha_cuidado = !empty($linha_cuidado->id) ? $linha_cuidado->id : null;
}
?>
@include('elements.layout.pdf.kit.header', array('agenda'=>$agenda->id, 'box' => false, 'logo_drsaude' => false, 'hidden_logo_header' => true))

<h1 class="title">TERMO DE CONSENTIMENTO LIVRE E ESCLARECIDO</h1>
<div style="text-align: right; margin-top: -10px">(Resolução CFM n. 1.931/2009 – Código de Ética Médica)</div>

<div class="" style="">

    <br/>
    <div>
        <p>A capsulotomia por YAG laser é um procedimento oftalmológico realizado na cápsula posterior do cristalino quando esta está opacificada. As opacidades podem aparecer após a cirurgia de
            catarata, na cápsula que fica atrás da lente intra-ocular, diminuindo a visão. O YAG laser faz a limpeza destas opacidades, com o intuito de melhorar a visão. </p><br/>
        <p>A pupila do olho a ser tratado deverá estar dilatada e o procedimento é indolor.</p><br/>
        <p>O médico oftalmologista pode prescrever colírios e/ou medicamentos para serem usados nos dias seguintes à realização do YAG laser para diminuir o risco de inflamação intra-ocular e de
            aumento de pressão intra-ocular.</p><br/>
        <p>As complicações que podem ocorrer após este procedimento incluem: edema macular, danos ou deslocamento da lente intra-ocular, edema de córnea, aumento da pressão intra-ocular e descolamento
            de retina.</p><br/>
        <p>O paciente deve adquirir e usar os colírios e/ou medicamentos prescritos para evitar a inflamação intra-ocular e o aumento da pressão intra-ocular. O médico oftalmologista irá orientar em
            quanto tempo o paciente deverá retornar a uma consulta com o oftalmologista para reavaliação e possível exame de óculos (refração).</p><br/>
        <p>Eu, ..........................................................................................................., tendo sido esclarecido quanto a tudo que indaguei a cerca do procedimento de
            YAG laser, declaro que compreendi todas as informações contidas neste termo e autorizo a realização do procedimento de YAG laser. Declaro que devo adquirir os colírio e/ou medicamentos
            prescritos pelo médico oftalmologista e realizar o retorno ao oftalmologista no tempo indicado pelo mesmo.</p><br/>
        <p>
         @include('elements.layout.kit.aux.lei-lgpd')
        </p><br/>
    </div>
</div>

<br/>
<div style="text-align: justify-all">
    São Paulo, ________ de _______________________________ de 202________
</div>

<br/><br/><br/>
@include('elements.agenda.ficha_atendimento.auxiliar.assinatura')


<div style="position: absolute; bottom: -50px">
    @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id])
</div>
</div>