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
<div style='page-break-before:always;' class="kit-impressao">
    @include('elements.layout.pdf.kit.header', array('agenda'=>$agenda->id, 'box' => false, 'logo_drsaude' => false, 'hidden_logo_header' => true))

    <h1 class="title">TERMO DE CONSENTIMENTO LIVRE E ESCLARECIDO PARA VASECTOMIA</h1>
    <div style="text-align: right; margin-top: -10px">(Resolução CFM n° 2.217, de 27 de setembro de 2018, modificada pelas Resoluções CFM nº 2.222/2018 e 2.226/2019 - Código de ètica Médica)</div>

    <div class="font-contrato-size" style="">

        <br/><br/>
        <div>
            <div><strong>Definição:</strong> É o procedimento cirúrgico que interrompe o fluxo de espermatozoides produzidos nos testículos (através do corte dos canais deferentes), deixando o homem
                estéril e impedindo a gravidez. O tempo de duração do procedimento é de aproximadamente 30 minutos.
            </div>
            <br/>
            Declaro que:<br/><br/>
            <div style="padding-left: 20px">
                <p>1 - Estou ciente que o art. 10 da Lei 9.263, de 12 de janeiro de 1996, que trata do planejamento familiar prevê que a esterilização voluntária só será possível em homens e mulheres
                    com capacidade civil plena e maiores de 25 anos de idade, ou pelo menos com dois filhos vivos, desde que observado o prazo mínimo de 60 dias entre a manifestação de vontade e o ato
                    cirúrgico, período no qual será propiciado à pessoa interessada acesso a serviço de regulação da fecundidade, incluindo aconselhamento por equipe multidisciplinar, visando
                    desencorajar a esterilização precoce. Que na vigência de sociedade conjugal, a esterilização depende do consentimento expresso de ambos os cônjuges.</p><br/>
                <p>2 - As contraindicações ao procedimento estão relacionadas aqueles que não preencham os requisitos da Lei.</p><br/>
                <p>3 - Apesar de ser uma intervenção cirúrgica relativamente simples, que pode ser feita com anestesia local, o corte dos ductos deferentes podem provocar inflamação, deixando o
                    escroto mais sensível, podendo provocar uma sensação dolorosa ao caminhar ou sentar, nos primeiros dias.</p><br/>
                <p>4 - A cirurgia produz efeito imediatamente, mas é aconselhável utilizar outros métodos contraceptivos até 3 (três) meses depois do procedimento, vez que ainda podem restar alguns
                    espermatozoides dentro dos canais, possibilitando uma gravidez. Normalmente, 40 dias após a cirurgia, com pelo menos 10 a 15 ejaculações neste período, os espermatozoides restantes
                    são eliminados, mas sempre é imperativo que se faça um espermograma completo após este período. A vasectomia não protege contra doenças sexualmente transmissíveis.</p><br/>
                <p>5 - A reversão do procedimento é possível até o 5º ano após o ato para ter-se maiores chances de sucesso, porém não é simples como a vasectomia (cirurgia esterilizadora masculina),
                    necessita de maior incisão, anestesia e equipamentos. Com o passar do tempo ela torna-se mais difícil, na medida que o corpo deixa de produzir espermatozoides e passa a produzir
                    anticorpos que eliminam os espermatozoides produzidos.</p><br/>
                <p>6 - Estou ciente que toda esterilização cirúrgica será objeto de notificação compulsória à direção do Sistema Único de Saúde, conforme dispõe a Lei 9.263 de 12 de janeiro de
                    1996.</p><br/>
                <p>7 - Estou ciente que o art. 15 da Lei 9.263, de 12 de janeiro de 1996, trata como crime a esterilização cirúrgica em desacordo com o estabelecido no art. 10 deste diploma legal.</p>
                <br/>
                <p>8 - Foram observadas todas as orientações necessárias para o procedimento, bem como foram fornecidas as informações sobre o estado de saúde do(a) paciente, incluindo doenças,
                    medicações as quais apresentou alergia, medicações em uso contínuo ou eventual, <strong>sem nada ocultar</strong>, tendo recebido orientação quanto à necessidade de suspensão ou
                    manutenção dessas medicações.</p><br/>
                <p>9 - Tive a oportunidade de fazer perguntas, que foram respondidas de maneira satisfatória. Entendo que não existe garantia sobre os resultados e que este termo não contempla todas
                    as complicações e riscos conhecidos e possíveis de acontecer neste procedimento/tratamento, mas apenas os mais frequentes.</p><br/>
                <p>10 - Li, recebi esclarecimentos e de forma compreensível pelo médico assistente e equipe, incluindo o direito de revogação do consentimento dado, desde que seja feito antes do
                    início da realização do procedimento proposto.</p><br/>
                <p>Desta forma, diante da compreensão do alcance dos benefícios, riscos, alternativas e pleno conhecimento do inteiro teor deste termo, <strong>AUTORIZO</strong> a realização da
                    vasectomia. Afirmo ainda que o presente termo integrará o prontuário médico, na hipótese de realização do procedimento/tratamento durante a internação hospitalar.</p><br/>
            </div>
        </div>

        <br/>
        <div style="text-align: justify-all">
            São Paulo, ________ de _______________________________ de 202________ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Hora:
            _______________
        </div>

        <br/>

        <div class="bloco">
            <table width="100%" class="" cellspacing="0" cellpadding="15">
                <tr>
                    <th colspan="2" class="title">Preenchimento Obrigatório pelo Paciente ou Representante legal</th>
                </tr>
                <tr>
                    <td width="50%" style="padding: 12px 3px !important;">Nome legível: ___________________________________</td>
                    <td width="50%" style="padding: 12px 3px !important;">Assinatura: _____________________________________</td>
                </tr>
                <tr>
                    <td style="padding: 8px 3px !important;">CPF: __________________________________________</td>
                    <td style="padding: 8px 3px !important;">Telefone: ______________________________________</td>
                </tr>
                <tr>
                    <td style="padding: 8px 3px!important;" colspan="2">Grau de Parentesco ou vínculo: ____________________________________________________________________</td>
                </tr>
            </table>
        </div>
        <p style="text-align: right; font-size: 12px !important; padding-top: -7px; padding-right: 5px">obrigatório nos casos de representação</p>

        <br/>
        <div class="bloco">
            <table width="100%" class="" cellspacing="0" cellpadding="15">
                <tr>
                    <th class="title">Preenchimento Obrigatório pelo Médico</th>
                </tr>
                <tr>
                    <td style="padding: 10px 3px!important;">
                        <p>Expliquei o procedimento ao qual o paciente acima referido está sujeito, ao próprio paciente ou seu representante legal, sobre os benefícios, riscos e alternativas, tendo
                            respondido às perguntas formuladas por eles. De acordo com o meu entendimento, o paciente e/ou seu representante legal, está em condições de compreender o que lhes foi
                            informado.</p>
                        <p style="text-align: right; font-size: 12px !important; padding-top: -7px; padding-right: 5px"><br/><br/>___________________________________________<br/>Assinatura e carimbo
                            do Médico</p>
                    </td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 10px">
            @include('elements.layout.kit.aux.lei-lgpd')
        </div>

        <br/>
        <div class="bloco">
            <table width="100%" class="" cellspacing="0" cellpadding="15">
                <tr>
                    <th colspan="2" class="title">Testemunhas</th>
                </tr>
                <tr>
                    <td width="50%" style="padding: 12px 3px !important; line-height: 30px !important;">
                        Nome legível: ___________________________________<br/>
                        CPF: __________________________________________<br/>

                        <p style="text-align: center"><br/><br/>
                            ________________________________<br/>
                            Assinatura
                        </p>
                    </td>
                    <td width="50%" style="padding: 12px 3px !important; line-height: 30px !important;">
                        Nome legível: ___________________________________<br/>
                        CPF: __________________________________________<br/>

                        <p style="text-align: center"><br/><br/>
                            ________________________________<br/>
                            Assinatura
                        </p>
                    </td>
                </tr>
            </table>
        </div>

        <br/>
        <br/>
        <p>
            Revogação: __________________________, ________/________/202____, às ________ horas e ________ minutos.
        </p>

        <p style="text-align: center"><br/><br/><br/><br/><br/><br/>
            ________________________________________________________________<br/>
            Paciente ou Representante Legal
        </p>

        <div style="position: absolute; bottom: -50px">
            @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id])
        </div>
    </div>
</div>