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
@include('elements.layout.pdf.kit.header', array('agenda'=>$agenda->id, 'box' => false, 'logo_drsaude' => false, 'hidden_logo_header' => true))

<h1 class="title">TERMO DE CONSENTIMENTO INFORMADO</h1>
<div style="text-align: right; margin-top: -10px">(Resolução CFM n. 1.931/2009 – Código de Ética Médica)</div>

<div class="" style="">
    <p><strong>A ESCLEROTERAPIA FUNCIONA PARA TODOS?</strong></p><br />
    <p>Para a maioria das pessoas, a escleroterapia é eficaz. Não há, entretanto, garantia que o tratamento será efetivo em todos os casos. Em aproximadamente 10% das pessoas o resultado não é satisfatório, ou seja, não há o desaparecimento das veias não desejadas após algumas sessões. Em situações pouco comuns, podem ocorrer complicações. As soluções esclerosantes utilizadas neste consultório são as mesmas descritas na literatura médica e são liberadas pela ANVISA (Agência Nacional de Vigilância Sanitária) para tal uso; consistem nas seguintes: solução glicosada a 50%, solução glicosada a 75%, polidocanol 0,5%, polidocanol 1%, polidocanol 3%, oleato de etanolamina 0,5%. O uso destas substâncias isoladamente ou em associação depende das características da pele e das veias de cada pessoa. EFEITOS COLATERAIS MAIS COMUNS.</p>
    <p>• Coceira: Dependendo da solução usada, é comum ocorrer coceira no trajeto venoso. Normalmente dura poucos minutos, porém, pode persistir por horas ou dias, casos nos quais são necessários  medicamentos para controlar este sintoma.</p>
    <p>• Hematomas e equimoses: Consistem em “manchas avermelhadas ou arroxeadas” na proximidade dos vasos injetados com duração de uma a quatro semanas. A meia elástica compressiva pode reduzir a extensão das manchas. Evitar a ingestão de álcool e de anticoagulantes antes da sessão reduz a incidência de equimoses.</p>
    <p>• Hiperpigmentação: Em quase todos os pacientes a veia injetada torna-se escura após a sessão. Este efeito, porém, dura poucos dias, período no qual a veia desaparece. Em alguns casos o escurecimento da veia pode persistir por até 12 meses, porém, desaparecem em mais de 90% das vezes. Em raras ocasiões pode ser permanente.</p>
    <p>• Dor: Ocorre dor devido à punção da veia com agulha e devido à injeção de esclerosante. Entretanto, é um procedimento tolerável e, na maioria das vezes, com dor discreta. A dor devido à punção é reduzida por tratar-se de agulha fina, tamanho 30 G ½. Existem esclerosantes que doem mais que outros, dependendo da solução usada e da concentração. Entretanto, na grande maioria dos casos, é um procedimento com dor discreta e tolerável.</p>
    <p>• Tromboflebite : Trata se da inflamação da veia, causada pela presença de trombose no seu interior, com sintomas de dor no trajeto venoso e vermelhidão da pele. Tem incidência entre 1 a 3% dos casos. • Trombose venosa profunda : Trata se da trombose de veias profunda, com risco de formar êmbolos ou coágulos em direção ao pulmão, configurando a embolia pulmonar. O ato de andar após a sessão previne a trombose venosa.</p>
    <p></p>
    <p>Declaro ainda, livre de qualquer coação e constrangimento, para não restar nenhuma dúvida quanto ao procedimento e a minha autorização em questão, que sou conhecedor dos seus princípios, indicações, riscos, complicações e resultados, declaro ainda, bem como o médico assistente e sua equipe forneceram-me, e aos meus acompanhantes e/ou familiares, as informações referentes a cada um desses itens, de conformidade com o disposto no Código de Ética Médica.          Não obstantemente, tendo ouvido, lido e aceito as explicações sobre os riscos e complicações mais comuns desta cirurgia e das chances de insucesso da mesma, declaro através de minha assinatura aposta neste documento, o meu pleno e irrestrito consentimento para sua realização, tudo isso na presença de testemunha.</p>

    <div style="margin-top: 5px">
        @include('elements.layout.kit.aux.lei-lgpd')
    </div>

    <div style="margin-top: 5px; text-align: right">
        {!! \App\Http\Helpers\Util::dateExtensoCidade($agenda->data, "São Paulo"); !!}

        <br/><br/><br/><br/><br/>
        @include('elements.layout.kit.assinaturas.paciente_e_responsavel')
    </div>

    <div style="position: absolute; bottom: -50px">
        @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id])
    </div>
</div>

<div style='page-break-before:always;'></div>