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
    $agenda->linha_cuidado = $linha_cuidado->id;
}
?>

<h1 class="title">TERMO DE CONSENTIMENTO</h1>
<div style="text-align: right; margin-top: -10px">(Resolução CFM n. 1.931/2009 – Código de Ética Médica)</div>

<div class="kit-impressao" style="margin-top: 10px">
    <p>
        <strong>PROCEDIMENTO:</strong>
        <br/>Videonasoscopia/Videolaringoscopia/Videonasolaringoscopia
        <br/>• Endoscópios rígido/flexível
        <br/>• Inspeção da via aérea superior: nariz, boca, rinofaringe, orofaringe e laringe.
        <br/>• Com ou sem biopsia, fotografia, filmagem, remoção de pólipos ou injeção de tratamento.
        <br/>• Biopsia será retida para análise anatomopatológica.
        <br/><br/><strong>BENEFÍCIOS</strong>
        <br/>Este procedimento tem como principal benefício a visualização de estruturas nasais, faringeas e laringeas, possibilitando um correto diagnóstico.
        <br/><br/><strong>RISCOS</strong>
        <br/>Este procedimento pode causar pequeno desconforto e/ou discretos sangramentos durante ou logo após o procedimento.
        <br/><br/><strong>DECLARAÇÃO DE CONFIRMAÇÃO:</strong>
        <br/>Você tem o direito de mudar de ideia a qualquer momento, mesmo após você ter lido este formulário.
        <br/>Eu recebi, li, discuti e entendi as informações colocadas neste formulário incluindo todos os benefícios e algum risco potencial.
        <br/>Eu concordo com o procedimento que envolve a passagem de um endoscópio pelo meu nariz ou pela minha boca, podendo assim o médico realizar meu exame.
        <br/>Eu entendo que o médico que realizará este procedimento tem experiência para realizar este exame.
        <br/>Eu compreendi que esses procedimentos diagnósticos/terapêuticos são importantes para o meu tratamento, mas eu posso sentir algum desconforto, dor ou pequeno sangramento. Sei que,
        apesar de tais riscos, este procedimento é necessário para dar continuidade ao tratamento de minha doença.
        <br/>Declaro que fui informado de todas as informações, em linguagem dentro da minha compreensão, e que todas as dúvidas foram esclarecidas. Declaro que forneci todas informações sobre o
        meu estado de saúde, das doenças possivelmente
        contagiosas, das medicações que sou alérgico e medicamentos dos quais faço uso eventual e contínuo.
        <br/><br/>Eu gostaria de ser submetido à:
        <br/>(&nbsp;&nbsp;&nbsp;&nbsp;) Anestesia local nasal e/ou garganta (&nbsp;&nbsp;&nbsp;&nbsp;) ou
        <br/>(&nbsp;&nbsp;&nbsp;&nbsp;)sedação (devo estar em jejum de 8 horas, entendo que raramente podem ocorrer reações à medicação e problemas
        cardiorrespiratórios durante o procedimento)
    </p>

    <div style="margin-top: 10px">
        @include('elements.layout.kit.aux.lei-lgpd')
    </div>

    <div style="margin-top: 5px; ">
        {!! \App\Http\Helpers\Util::dateExtensoCidade($agenda->data, "São Paulo"); !!}

        <br/><br/><br/>
        @include('elements.layout.kit.assinaturas.paciente_e_responsavel')
    </div>

    <div style="position: absolute; bottom: -50px">
        @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id])
    </div>
</div>
