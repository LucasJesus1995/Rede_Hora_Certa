<?php

if (!isset($kit_white) || !$kit_white) {
    $arena = (Object)\App\Arenas::get($agenda->arena);
} else {

}
?>
<div style='page-break-before:always;' class="kit-impressao">
    @include('elements.layout.pdf.kit.header', array('agenda'=>$agenda->id, 'box' => false, 'logo_drsaude' => false, 'hidden_logo_header' => true))

    <div class="bloco" style="margin: 0; margin-top: 10px;">
        <h2 style="text-align: left">Orientações</h2>
        <div style="margin: 10px 5px; line-height: 17px; height: 20px; font-style: italic ">
            @if($agenda->linha_cuidado == 9)
                1. Cuidados com a pós escleroterapia, e retorno para reavaliação
            @else
                1. Cuidados com a ferida operatória, e retorno para reavaliação
            @endif
        </div>
    </div>

    <table width="100%">
        <tr>
            <td width="50%">
                <div class="bloco" style="margin: 0; margin-top: 10px;">
                    <h2 style="text-align: left">Condições de alta</h2>
                    <div style="margin: 10px 5px; line-height: 17px; height: 35px; font-style: italic ">
                        @if($agenda->linha_cuidado == 9)
                            Consciente e orientado, e sem queixas
                        @else
                            Consciente e orientado, após analgesia e aceitação da dieta
                        @endif
                    </div>
                </div>
            </td>
            <td width="50%">
                <div class="bloco" style="margin: 0; margin-top: 10px;">
                    <h2 style="text-align: left">CID - Classificação Internacional de Doenças</h2>
                    <div style="margin: 10px 5px; line-height: 17px; height: 35px; font-style: italic ">
                        @include('elements.layout.kit.cirurgico.kits.alta-hospitalar-cids')
                    </div>
                </div>
            </td>
        </tr>
    </table>


    <div class="bloco" style="margin: 0; margin-top: 10px;">
        <h2 style="text-align: left">
            @if($agenda->linha_cuidado == 47)
                Orientações pós-operatória – Vasectomia
            @elseif($agenda->linha_cuidado == 9)
                ORIENTAÇÕES PÓS TRATAMENTO ESCLEROSANTE DE VARIZES
            @else
                ORIENTAÇÕES PÓS CIRÚRGICA
            @endif
        </h2>
        <div style="margin: 10px 5px; line-height: 17px; font-style: italic ">
            @if(in_array($agenda->linha_cuidado, [19, 45]))
                @if(in_array($sub_especialidade, [2]))
                    {{--                    - Manter o oclusor no olho operado por 24 horas e nesse período procurar manter os olhos fechados;<br>--}}
                    - Retirar o oclusor com cuidado, é comum que saia um pouco de sangue;<br>
                    - Usar apenas gazes ou lenços descartáveis para higiene dos olhos (não usar panos ou toahas);<br>
                    - Tomar remédios normalmente;<br>
                    {{--                    - Não assistir televisão nas primeiras 48 horas;<br>--}}
                    - Pingar os colírios nos horários, exceto durante a madrugada (agitá-los levemente antes do uso, e
                    não encostar a ponta dos colírios na mãos, olhos, rosto ou objetos);<br>
                    - Lavar as mãos antes de pingar os colírios;<br>
                    - Não dormir do lado do olho operado por 07 dias;<br>
                    - É comum ter dor e sensação de areia nos olhos, durante os três primeiros dias, nestes casos tomar
                    a mesma remédio que usa para dor de cabeça;<br>
                    {{--                    - Só é permitido lavar os cabelos após 02 dias da cirurgia e quando lavar não deixar escorrer água nos olhos.<br>--}}
                @else
                    Em Casa: logo após sua cirurgia permaneça em repouso.<br/>
                    • Pingue os colírios de acordo com a orientação médica (mesmo com o curativo levante-o e pingue);
                    <br/>
                    • Em caso de dor, usar o analgésico (Dipirona, Paracetamol) de acordo com preferência;<br/>
                    • Não abaixar a cabeça bruscamente e não pegar peso;<br/>
                    • Nada de exercícios físicos durante 45 dias;<br/>
                    • Sempre lavar as mãos antes de instilar colírios;<br/>
                    • Não esfregar os olhos ou passar maquiagem;<br/>
                    • Não dormir em cima do olho operado;<br/>
                    • Dirigir somente após 15 dias da cirurgia;<br/>
                    • Não ingerir qualquer tipo de bebida alcoólica por 45 dias;<br/>
                    • Durante o banho evitar a entrada em excesso de água nos olhos;<br/>
                    • Permanecer 07 dias afastado (a) de atividades domésticas (cozinhar, lavar, passar);<br/>
                    • Não precisa pingar durante a madrugada;<br/>
                    • Usar óculos escuro;<br/>
                @endif
            @endif

            @if(in_array($agenda->linha_cuidado, [9, 49]))
                <strong>Em Casa</strong>: Se a residência tem escadas não há nenhum problema em subi-las devagar.
                Procurar ficar um pouco mais em repouso nos 2 primeiros dias após a cirurgia: deitado
                ou sentado com apoio sob as pernas. Apesar disso, você está liberado para movimentar-se pela casa,
                ir ao banheiro,
                fazer refeições, atender ao telefone, etc. e deve andar de 10 a 15 minutos cada 1 hora. Depois, à medida
                que se sentir bem este tempo pode ir sendo aumentado progressivamente;
                Não molhar o curativo. Retirá-los após 1 dias da cirurgia e continuar a usar meia elástica, retirar ao
                dormir;<br/>
                Após a retirada do curativo os banhos podem ser normais;<br/>
                Em caso de dor, utilizar analgésicos conforme prescritos;<br/>
                <strong>Sol</strong>: evitar exposição (praia, piscina, etc.) durante 30 dias;<br/>
                Manter o uso da meia elástica, somente ao dormir retirar;<br/>
            @endif

            @if(in_array($sub_especialidade, [4]))
                . É importante seguir todas as orientações dadas tais como cuidados com a ferida, limpeza e uso de
                medicamentos analgésicos ou anti-inflamatórios para a dor, prescritos pelo médico;
                <br/>
                . Não fazer uso de bebida alcoólica por 45 dias;<br/>
                · Certifique-se de manter o local da incisão limpo e bem protegido de uma possível lesão;<br/>
                . Tente limitar o movimento que possa forçar o ferimento e as suturas;<br/>
                . Não realizar esforços como dirigir ou carregar peso por 7 a 10 dias;<br/>
                . Não pegar peso;<br/>
                . Vir para o retorno conforme orientação da enfermagem;<br/>
                . Não realizar atividades físicas mais intensas, como esportes, só após 1 mês.<br/>
            @endif

            @if(in_array($sub_especialidade, [5]) || in_array($agenda->linha_cuidado, [46]))
                . É importante seguir todas as orientações dadas, tais como: cuidados com a ferida, limpeza e uso de
                medicamentos (se indicado);<br/>
                . Você poderá retornar a atividades leves no dia de sua cirurgia, de acordo com a orientação médica;
                <br/>
                · Certifique-se de manter o local da incisão limpo e bem protegido de uma possível lesão;<br/>
                . Tente limitar o movimento que possa forçar o ferimento e as suturas;<br/>
                . Em caso de dor, usar o analgésico (Dipirona, Paracetamol) de acordo com preferência;<br/>
                . Não pegar peso;<br/>
                . Vir para o retorno conforme orientação da enfermagem.<br/>
            @endif

            @if(in_array($agenda->linha_cuidado, [47]))
                . Curativo manter por 48 horas, e trocar quando molhado;<br/>
                . Não tenha relações sexuais por pelo menos 2 semanas;<br/>
                · Evitar exercícios físicos intensos por pelo menos 2 semanas;<br/><br/>
            @endif

            • <strong>Em caso de dúvidas entre em contato
                Telefone @if(!empty($arena->telefone)) {!! \App\Http\Helpers\Mask::telefone($arena->telefone) !!}   @else
                    _____ ________-________  @endif
                <br/><br/></strong>

            <br/>
            <div>Retorno pós cirúrgico ______/ ______/____________ às ____________________</div>

        </div>
    </div>

    <div style="position: absolute; bottom: -50px">
        @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id])
    </div>
</div>