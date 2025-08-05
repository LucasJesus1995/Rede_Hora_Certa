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

    <h1 class="title">TERMO DE USO E POLÍTICA DE PRIVACIDADE</h1>
    <div style="text-align: right; margin-top: -10px">(Resolução CFM n. 1.931/2009 – Código de Ética Médica)</div>

    <div class="" style="">
        <p style="margin-bottom: 10px">
            <strong>INTRODUÇÃO</strong><br/>
            A Associação Beneficente Ebenézer – CIES GLOBAL, está comprometida com a proteção de dados e informações pessoais que são compartilhados pelos usuários conforme definido abaixo. Essa
            política define como os dados são protegidos nos processos de coleta, registro, armazenamento, uso, compartilhamento, enriquecimento e eliminação, para além da Lei nº 13.709/2018 (Lei
            Geral de Proteção de Dados).
            <br/>Recomendamos a leitura cuidadosa deste documento.
        </p>

        <p style="margin-bottom: 10px">
            <strong>COLETA DE DADOS PESSOAIS </strong><br/>
            A coleta de dados pessoais é necessária para que o CIES ofereça serviços e funcionalidades adequados às necessidades dos usuários, bem como para personalizar serviços, fazendo com que sua
            experiência seja a mais cômoda e satisfatória possível.
            <br/>Ao solicitar dados pessoais e dados pessoais sensíveis, o CIES poderá solicitar o consentimento do usuário por meio do Termo de Consentimento, seguindo e cumprindo as obrigações
            legais e regulatórias.
            <br/>Em atendimentos presenciais, para dar entrada a solicitações e atendimentos, é necessário, igualmente, o fornecimento de dados pessoais, que serão coletados por um atendente
            responsável, que realizará o registro das informações em sistema cadastral.
            <br/>Os dados pessoais solicitados devem ser informados para que seja possível dar sequência ao seu pedido ou atendimento. Outros dados pessoais e dados pessoais sensíveis poderão ser
            solicitados, em seguida, de acordo com o atendimento selecionado.
        </p>

        <p style="margin-bottom: 10px">
            <strong>UTILIZAÇÃO DE DADOS PESSOAIS </strong><br/>
            O CIES é a entidade responsável pelo tratamento dos dados pessoais dos seus usuários ou por seu encaminhamento às entidades designadas.
            <br/>Os dados pessoais dos seus usuários coletados, incluindo aqueles direta ou indiretamente relacionados com a sua saúde, serão tratados para efeitos de prestação de cuidados integrados
            de saúde, incluindo gestão dos sistemas e demais serviços, auditoria e melhoria contínua dos mesmos, podendo ser relacionados com os dados das demais unidades do CIES que possuam o mesmo
            objetivo.
            <br/>O CIES poderá tratar os dados pessoais coletados para as finalidades previstas no consentimento informado, tais como procedimentos realizados por profissionais da saúde e serviços de
            saúde, comunicações relevantes para a promoção da sua saúde, pesquisas de satisfação para melhoria de nossos serviços, entre outros.
        </p>

        <p style="margin-bottom: 10px">
            <strong>COMPARTILHAMENTO DE DADOS PESSOAIS</strong><br/>
            Haverá transmissão e comunicação de dados pessoais entre os departamentos do CIES, com acesso de colaboradores designados, sempre que necessário, para possibilitar a melhor experiência e
            atendimento à necessidade do usuário.
            <br/>O CIES poderá, ainda, transmitir os seus dados a entidades que de alguma forma precisem atuar colaborando para sua melhor experiência e para o melhor atendimento, como por exemplo,
            órgãos reguladores, laboratórios ratificando assumir o compromisso de junto a seus designados exigir aderência às regulamentações aplicáveis.
            <br/>Poderemos também transmitir dados pessoais dos Usuários a terceiros quando tais comunicações de dados se tornem necessárias ou adequadas (i) à luz da lei aplicável, (ii) no
            cumprimento de obrigações legais/ordens judiciais, (iii) por determinação da Autoridade Nacional de Proteção de Dados ou de outra autoridade de controle competente, ou (iv) para responder
            a solicitações de autoridades públicas ou governamentais.
        </p>

        <p style="margin-bottom: 10px">
            <strong>CONSERVAÇÃO DE DADOS PESSOAIS</strong><br/>
            Os dados são conservados pelo período estritamente necessário para cada uma das finalidades descritas acima e/ou de acordo com prazos legais vigentes. Em caso de litígio pendente, os dados
            podem ser conservados até trânsito em julgado da decisão.
            <br/>Adicionalmente, o CIES afirma que manterão em funcionamento todos os meios técnicos ao seu alcance para evitar a perda, má utilização, alteração, acesso não autorizado e apropriação
            indevida dos dados pessoais de seus usuários. Em qualquer caso, note-se que, circulando os dados em rede internet aberta, não é possível eliminar totalmente o risco de acesso e utilização
            não autorizados, pelo que o usuário deverá programar medidas de segurança adequadas para a navegação no website.
        </p>

        <p style="margin-bottom: 10px">
            <strong>DIREITOS DOS USUÁRIOS</strong><br/>
            Nos termos da legislação aplicável, o titular do dado poderá a qualquer tempo solicitar o acesso aos dados que lhe digam respeito, bem como a sua retificação, eliminação ou a limitação de
            uso do dado pessoal, a portabilidade dos seus dados, ou ainda opor-se ao seu tratamento, exceto nos casos previstos em lei. Poderá exercer estes direitos mediante pedido escrito dirigido
            ao e-mail dpo@CIES.br
        </p>

        <p style="margin-bottom: 10px">
            <strong>RECLAMAÇÕES E DÚVIDAS</strong><br/>
            Caso tenha qualquer dúvida relacionada com o tratamento dos seus dados pessoais e com os direitos que lhe são conferidos pela legislação aplicável e, em especial, referidos nessa Política,
            poderá acionar o CIES através do e-mail: dpo@CIES.br
        </p>

        <p style="margin-bottom: 10px">
            <strong>ALTERAÇÕES A POLÍTICA DE PRIVACIDADE</strong><br/>
            Poderemos alterar esta Política de Privacidade de dados a qualquer momento. Estas alterações serão devidamente disponibilizadas e, caso represente uma alteração substancial relativamente à
            forma como os seus dados serão tratados, o CIES manterá contato conforme dados disponibilizados.
        </p>

        <div style="margin-top: 5px">
            @include('elements.layout.kit.aux.lei-lgpd')
        </div>


        <div style="position: absolute; bottom: -50px">
            @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id])
        </div>
    </div>
</div>

<div style='page-break-before:always;'></div>
