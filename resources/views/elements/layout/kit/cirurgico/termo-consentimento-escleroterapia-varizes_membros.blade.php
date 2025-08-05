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
<div style='page-break-before:always;'>
    @include('elements.layout.pdf.kit.header', array('agenda'=>$agenda->id, 'box' => false, 'logo_drsaude' => false, 'hidden_logo_header' => true))

    <h1 class="title">ESCLEROTERAPIA DE VARIZES DOS MEMBROS INFERIORES</h1>

    <div class="" style="">
        <p style="margin-bottom: 10px">
            <strong>O que é?</strong><br/>
            Procedimento clínico, realizado por médico Angiologista ou Cirurgião Vascular.
            <br/>Consiste na utilização de agente esclerosante químico para o tratamento de varizes dos membros inferiores. Os esclerosantes utilizados são a glicose hipertônica 75% e o polidocanol,
            na forma liquida ou de espuma.
        </p>

        <p style="margin-bottom: 10px">
            <strong>Como é feito?</strong><br/>
            O agente esclerosante é injetado diretamente no vaso a ser tratado. Ele produz uma reação inflamatória na parede da veia e a consequente fibrose, fazendo com que o vaso se feche.
        </p>

        <p style="margin-bottom: 10px">
            <strong>Qual o objetivo do tratamento? O tratamento é efetivo?</strong><br/>
            O objetivo desse tratamento é a melhora da circulação sanguínea. É tratado a insuficiência venosa superficial (varizes), e para essa finalidade é efetivo.
        </p>

        <div style="margin-bottom: 10px">
            <strong>Quais os cuidados após o procedimento?</strong>
            <ul style=" margin-left: 10px">
                <li>- Recomendamos o uso de meias elásticas.</li>
                <li>- Evitar exposição ao sol.</li>
                <li>- Evitar grandes esforços na primeira semana.</li>
                <li>- Não é necessário o repouso absoluto, sendo recomendado andar desde o primeiro dia.</li>
                <li>- Se necessário para a recuperação, será fornecido atestado médico.</li>
            </ul>
        </div>

        <p style="margin-bottom: 10px">
            <strong>O resultado é imediato?</strong><br/>
            O resultado não é imediato. As veias tratadas podem sofrer uma inflamação e irão diminuir com o passar das semanas. Podem inicialmente ficar endurecidas e avermelhadas. Áreas acastanhadas
            podem surgir no local das aplicações. Essas alterações vão melhorando com o passar das semanas. Porém é importante observar que cada pessoa responde de uma maneira.
            <br/>Pode ser necessário novas aplicações. Durante todo o tratamento, o paciente será acompanhado por um médico vascular.
        </p>

        <p style="margin-bottom: 10px">
            <strong>Qual a vantagem da Escleroterapia?</strong><br/>
            O tratamento das varizes com escleroterapia tem como vantagem não necessitar de internação hospitalar. E um procedimento ambulatorial, ou seja, realizado em consultório médico.
        </p>


        <p style="margin-bottom: 10px">
            <strong>Existem complicações?</strong><br/>
            Como todo procedimento médico, complicações podem ocorrer. Assim para maior segurança do procedimento é importante a avaliação do Médico Vascular e o seguimento correto das orientações pós
            procedimento.
            <br/>As principais complicações que podem ocorrer na escleroterapia são hiperpigmentação e aumento da sensibilidade da pele, inchaço (edema), trombose, alergia ao medicamento e infecção.
        </p>


        <div class="bloco">
            <h2 style="text-align: left">Orientações</h2>
            <div class="content" style="margin: 10px">
                <ul>
                    <li>- Vir com acompanhante (maior de 18 anos e com documento com foto) e chegar com 30 minutos de antecedência;</li>
                    <li>- Não é necessário jejum. Recomenda-se que faça uma refeição leve</li>
                    <li>- Tomar todas as medicações normalmente</li>
                    <li>- Trazer todos os exames médicos, inclusive o ultrassom doppler venoso dos membros inferiores</li>
                    <li>- Trazer a meia elástica conforme a prescrição médica</li>
                    <li>- Usar roupa confortável (evitar calças justas)</li>
                    <li>- Não aplicar cremes ou hidratantes sobre as pernas antes do procedimento</li>
                    <li>- Caso a paciente saiba ser portadora de algum tipo de alergia: iodo, esparadrapo, micropore, analgésicos, anti inflamatórios, antibióticos, ou quaisquer medicamentos, deverá
                        comunicar ao médico com antecedência
                    </li>
                    <li>- Se faz uso de anticoagulantes (marevan, xarelto, eliquis) avise com antecedência para verificar a necessidade de suspensão</li>
                </ul>
            </div>
        </div>

        <div style="position: absolute; bottom: -50px">
            @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id])
        </div>
    </div>
</div>


<div style='page-break-before:always;'></div>