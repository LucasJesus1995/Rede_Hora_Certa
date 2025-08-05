<?php
if (!$kit_white) {
    $paciente = (Object)\App\Pacientes::get($agenda->paciente);
} else {
    $paciente = new stdClass();
    $paciente->nome = "PACIENTE";
    $paciente->nome_social = null;
    $paciente->rg = null;
}
?>
@include('elements.layout.pdf.kit.header', array('agenda'=>$agenda->id, 'box' => false, 'logo_drsaude' => false, 'hidden_logo_header' => true))

<h1 class="title">TERMO DE CONSENTIMENTO INFORMADO</h1>

<div class="font-contrato-size" style="">
    <p>Este termo foi elaborado pelo CIES Global para esclarecer de forma clara os possíveis riscos e benefícios da cirurgia de pterígio. Leia-o com atenção. Se não conseguir ler, peça a um familiar
        ou alguma pessoa de sua confiança que o leia em voz alta para você. Caso haja dúvidas após a leitura, anote-as e a pergunte ao médico antes da cirurgia.</p>

    <br/><br/>Eu <strong>@if(!empty($paciente->nome_social)) {!! $paciente->nome_social !!} @else {!!  $paciente->nome !!} @endif</strong> portador(a) da identidade n&ordm; @if(!empty($paciente->rg))
        <strong>{!! $paciente->rg !!}</strong>  @else _______________________  @endif portador (a) de pterígio no olho _______________________, declaro ter sido orientado (a) sobre meu diagnóstico e
    sobre a cirurgia a ser realizada.
</div>

<br/>
<h1 class="title">O QUE É PTERÍGIO?</h1>
<div class="font-contrato-size" style="">Pterígio, também conhecido como “carne esponjosa do olho”, é um espessamento da conjuntiva, que é uma membrana mucosa que cobre o branco do olho (esclera). Ele
    pode crescer sobre a córnea (parte frontal transparente do olho). Acredita-se que esses inchaços são causados pela radiação ultravioleta, exposição ao vento e a agentes irritantes, além da
    síndrome do olho seco.
</div>

<br/>
<h1 class="title">EXISTEM TRATAMENTOS ALTERNATIVOS?</h1>
<div class="font-contrato-size" style="">Em muitos casos, algumas medidas de proteção do olho resolvem o problema no início, bastando que se reduza a exposição à radiação UV, à poeira, ao vento e o
    uso de óculos de proteção UV. Também são úteis os colírios lubrificantes. Às vezes, os colírios podem ser usados para reduzir a inflamação.<br/><br/>Você deve entender que, apesar da remoção, o
    pterígio pode reaparecer. Se a volta do pterígio ocorrer, uma cirurgia adicional pode ser necessária.
</div>

<br/>
<h1 class="title">COMO É A CIRURGIA PARA REMOÇÃO DO PTERÍGIO?</h1>
<div class="font-contrato-size" style="">O pterígio é removido com bisturi e em geral é realizado um transplante de conjuntiva de outra porção do mesmo olho. Pontos com fio muito fino (nylon 10.0) ou
    o uso de colas especiais são usados na maioria dos casos.
</div>

<br/>
<h1 class="title">QUAIS SÃO OS RISCOS DO PROCEDIMENTO?</h1>
<div class="font-contrato-size" style="">Todo procedimento médico possui risco inerente ao ato. As complicações que pode ocorrer no período inicial ou posteriormente podem incluir quadros como baixa
    visão irreversível, não recuperação da transparência da córnea, hemorragia, infecção, visão dupla, afinamento escleral e danos relacionados ao procedimento anestésico.
</div>
<br/>
<div class="font-contrato-size" style="">A principal complicação, por ser mais frequente, é a recidiva do quadro, ou seja, o pterígio pode voltar a crescer. Você pode necessitar de tratamento
    adicional ou cirurgia para tratar essas complicações.
</div>

<br/>
<h1 class="title">APÓS A CIRURGIA COMO SERÁ O MEU ACOMPANHAMENTO?</h1>
<div class="font-contrato-size" style="">O paciente deve seguir de forma correta as orientações médicas e comparecer as consultas de retorno no próprio CIES Global.</div>

<div style="position: absolute; bottom: -50px">
    @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id])
</div>

<div style='page-break-before:always;'>
    <h1 class="title">DECLARAÇÃO DO PACIENTE</h1>
    <div class="font-contrato-size" style="">
        <div style="margin-left: 20px">
            <p style="padding-top: 5px">- Declaro que fui informado(a) pelo médico sobre a cirurgia a ser efetuada, tratamentos alternativos e que ele respondeu às minhas preocupações específicas
                sobre o assunto.</p>
            <p style="padding-top: 5px">- Declaro que recebi os devidos esclarecimentos do médico sobre os riscos e complicações específicos relacionadas à minha pessoa, os quais considerei ao decidir
                fazer o procedimento.</p>
            <p style="padding-top: 5px">- Concordo com quaisquer outros procedimentos considerados necessários no julgamento do médico durante o procedimento.</p>
            <p style="padding-top: 5px">- Declaro que fui orientado (a) sobre todos os itens acima (total de duas páginas) e compreendi tudo, não restando dúvidas.</p>
        </div>
    </div>
</div>

<p style="text-align: center;"><br/><br/>
    <strong style=" font-size: 14px !important;">Autorizo o CIES Global, por meio dos membros da sua equipe de oftalmologia, a realizar o <br/>procedimento descrito acima e outros que se fizerem
        necessários</strong>
</p>

<div>
    <table width="100%">
        <tr>
            <td width="30%" colspan="2">
                <div style="margin-top: 80px; border-top: 1px solid #000; width: 250px; text-align: center">
                    @if(!empty($paciente->nome_social)) {!! $paciente->nome_social !!} @else {!!  $paciente->nome !!} @endif
                </div>
            </td>
        </tr>
    </table>
    <table width="100%" class="">
        <tr>
            <td width="200px">&nbsp;</td>
            <td width="200px" style="text-align: right">
                <div style="margin-top: 50px; border-top: 1px solid #000;  text-align: center">
                    RESPONSÁVEL
                </div>
            </td>
            <td width="100px">
                <div style="margin-top: 37px; text-align: right">
                    RG: _______________________
                </div>
            </td>
        </tr>
        <tr>
            <td></td>
            <td colspan="2">
                <div style="margin-top: 50px; border-top: 1px solid #000; text-align: center">
                    NOME LEGÍVEL DO ACOMPANHANTE
                </div>
            </td>
        </tr>
    </table>
</div>

<br/>
<h1 class="title">DECLARAÇÃO DO MÉDICO</h1>
<div class="font-contrato-size" style="">
    <div style="margin-left: 20px">
        <p style="padding-top: 5px">- Declaro que expliquei as eventuais consequências do procedimento que será realizado e esclareci os riscos de interesse particular para o paciente.</p>
        <p style="padding-top: 5px">- Declaro que dei ao paciente a oportunidade de perguntar sobre a patologia e o procedimento e respondi a todas as indagações.</p>
    </div>
</div>


<p style="margin-top: 120px">@include('elements.layout.kit.cirurgico.assinatura-carimbo-medico')</p>


<div style="position: absolute; bottom: -50px">
    @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id])
</div>
