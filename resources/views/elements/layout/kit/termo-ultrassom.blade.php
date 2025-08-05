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

<h1 class="title">TERMO DE CONSENTIMENTO DE ULTRASSOM</h1>
<div style="text-align: right; margin-top: -10px">(Resolução CFM n. 1.931/2009 – Código de Ética Médica)</div>

<div class="" style="margin-top: 10px">
    <p>
        1. Fui informado(a) sobre como é realizado este procedimento e ficou claro qual o propósito do procedimento;
        <br />2. Recebi as instruções relativas aos cuidados que deverei tomar após a realização do mesmo (higiene local, curativo se for o caso) e demais orientações médicas que devo seguir a fim de alcançar o melhor resultado;
        <br />3. Estou ciente das complicações e dos riscos que eventualmente podem ocorrer e que deverei tratar;
        <br />4. Caso tal ocorra complicações, devo procurar auxílio médico;
        <br />5. Declaro que além das informações constantes neste Termo, fui esclarecido(a) verbalmente pelo médico a respeito do meu tratamento e pude esclarecer minhas dúvidas;
        <br />6. Estou ciente que o tratamento não se limita a este procedimento, que o mesmo não garante a cura e que a dependendo da evolução da doença, o médico pode alterar as condutas já tomadas. Estou ciente que deverei retornar em consulta médica, para dar prosseguimento ao tratamento;
        <br />7. Compreendi os termos médicos e concordo com os termos deste documento;
        <br />8. Declaro que nada omiti em relação a minha saúde e que esta declaração passe a fazer parte da minha ficha clínica ou fique na guarda pessoal do(a) meu médico(a), ficando autorizado a utilizá-la em qualquer época, no amparo e na defesa de seus direitos, sem que tal utilização implique em qualquer tipo de ofensa.
    </p>

    <div style="margin-top: 10px">
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
