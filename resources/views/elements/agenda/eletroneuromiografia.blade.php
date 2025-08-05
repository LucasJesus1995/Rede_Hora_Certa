<div id="impressao" class="impressao-small" style="">

    <div class="margin10 description">
        <h1 class="align-center"><strong>TERMO DE CONSENTIMENTO EM ELETRONEUROGRAFIA</strong></h1><br/>
        <p>A eletroneuromiografia é um exame que estuda o “funcionamento” dos nervos periféricos e músculos.</p>
        <p><strong>- Como é realizado?</strong>
            <br/>O paciente fica deitado numa maca e sobre a área a ser examinada, são aplicados estímulos elétricos e uma pequena agulha.</p>
        <p><strong>- Estas agulhas causam dor?</strong>
            <br/>Podem causar um pequeno desconforto semelhante a uma picada de inseto, de curta duração. Ocasionalmente pode ocorrer um pequeno sangramento no local da introdução da agulha, sem conseqüências futuras.</p>
        A sensação de choque elétrico causado pelo estímulo é aproximadamente mil vezes MENOR que o choque causado por tomada elétrica caseira.
        <p><strong>- Estas agulhas são reutilizáveis?</strong>
            <br/>NÃO. As agulhas são de uso individual e descartável.</p>
        <p>- Colabore com o seu médico permanecendo em silêncio durante a realização do exame e evite movimentos desnecessários, isto agilizará a execução e qualidade do exame.</p>
        <p>- A realização do exame não provoca nenhum efeito colateral imediato ou tardio e a dor residual, nos pontos de introdução das agulhas, desaparecem em poucas horas.</p>
        <p>- O acompanhante só será permitido para menores de 18 anos ou pacientes que necessitem de ajuda para transferência e vestuário.</p>
        <p><strong>Existem contraindicações?</strong>
            <br/>Relativas:
            <br/>Uso de marca-passo impede a aplicação de estímulos em pontos proximais.Pacientes com cateter intracardíaco não podem receber estimulação elétrica.
            </br>Pacientes com plaquetopenia abaixo de 50.000/mm ou distúrbios de coagulação.
            <br/>Pacientes em uso de anticoagulantes, nos quais o tempo de protrombina ou parcial de tromboplastina seja igual ou superior a duas vezes o valor normal.
        <p>
        <p>
            Absolutas:<br />
            Marca-passo do tipo desfibrilador, marca-passo externo.<br />
            Pele com solução de continuidade e erisipela.
        </p>
        <p>
            <strong>Quais são as limitações do exame?</strong><br />
            Crianças pequenas podem necessitar de sedação para a realização do estudo dos nervos periféricos.
        </p>
        <p>
            <strong>Qual o preparo necessário?</strong><br />
            O paciente é orientado a não utilizar cremes hidratantes no dia da realização do exame.<br />
            Agradecemos a sua confiança.
        </p>
        <br>
        <p>Declaro que li e compreendi todas as informações acima e autorizo a realização do procedimento de eletroneuromiografia.</p>
        <p>
            Declaro ainda, livre de qualquer coação e constrangimento, para não restar nenhuma dúvida quanto ao procedimento e a minha autorização em questão, que sou conhecedor dos seus princípios, indicações, riscos, complicações e resultados, declaro ainda, bem como o médico assistente e sua equipe forneceram-me, e aos meus acompanhantes e/ou familiares, as informações referentes a cada um desses itens, de conformidade com o disposto no Código de Ética Médica. Não obstantemente, tendo ouvido, lido e aceito as explicações sobre os riscos e complicações mais comuns desta cirurgia e das chances de insucesso da mesma, declaro através de minha assinatura aposta neste documento, o meu pleno e irrestrito consentimento para sua realização, tudo isso na presença de testemunha.
        </p>

        <div style="margin-top: 10px">
            @include('elements.layout.kit.aux.lei-lgpd')
        </div>
    </div>
    <div style="margin-top: 15px; text-align: right">
        {!! \App\Http\Helpers\Util::dateExtensoCidade(@$agenda->data, "São Paulo"); !!}

        <br/><br/><br/><br/>
        @include('elements.layout.kit.assinaturas.paciente_e_responsavel')
    </div>
</div>