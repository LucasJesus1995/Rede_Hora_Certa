<div>
    @include('elements.layout.pdf.kit.header', array('agenda'=>$agenda->id, 'box' => false, 'exibe_linha' => true, 'logo_drsaude' => true))
    <div style="">
        <h1 class="title">TERMO DE CONSENTIMENTO </h1>
        <div style="text-align: right; margin-top: -10px">(Resolução CFM n. 1.931/2009 – Código de Ética Médica)</div>

        <div class="font-contrato-size" style="margin-top: 10px">
            <p>
                <br />Fui informado de que a vasectomia é um método cirúrgico de interrupção de fertilidade masculina, causada pela
                secção dos canais deferentes interrompendo assim a passagem dos espermatozoides para o líquido ejaculado.
                <br />Fui informado que as complicações que poderão ocorrer são sangramentos que provocam elevação da pele no local
                operado, manchas escuras no escroto e ou no pênis, dor e infecção. Estes transtornos são raros e passageiros e qualquer sinal
                diferente fui orientado a contatar meu médico ou pronto atendimento nos hospitais da rede credenciada.
                <br />Apesar de poder ser tentada a reversão futura dessa cirurgia, ou seja, a recanalização do ducto deferente,estou ciente
                que quanto maior o tempo de interrupção, menor a taxa de sucesso em readquirir fertilidade. A reversão desta cirurgia, ou
                seja, a recanalização do ducto deferente, não tem cobertura pelo contrato de plano de saúde vigente.
                <br />A vasectomia é um método de esterilização permanente. Existe uma pequena possiblidade (1 em cada 2000
                operações) de ocorrer recanalização espontânea, ou seja, de ocorrer a passagem espontânea de espermatozoides de um ducto
                para outro e voltarem a ser ejaculados e ocasionarem uma gravidez.
                <br />O método não interfere na função sexual, nem causa impotência sexual. Até o momento não se conhece nenhuma
                doença que ocorra mais frequentemente em homens vasectomizados.
                <br />O paciente só poderá retomar sua atividade sexual sem qualquer forma de anticoncepção quando o exame de
                espermograma mostrar ausência de espermatozoides no ejaculado. Isso só ocorre após uma média de 25 ejaculações, que é o
                número necessário para “esvaziar” o trato genital que está á frente de onde foi feita a secção do ducto deferente.
                <br />Diante do exposto, comunico que entendi as explicações que me foram prestadas em linguagem clara e simples, tive
                oportunidade de fazer perguntas e esclarecer minhas dúvidas.
                <br />Declaro agora que estou satisfeito com as informações recebidas e que compreendo o alcance e riscos do procedimento
                cirúrgico. Por isso, assumo integralmente a responsabilidade por minha opção de esterilização cirúrgica.
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
    </div>
</div>
