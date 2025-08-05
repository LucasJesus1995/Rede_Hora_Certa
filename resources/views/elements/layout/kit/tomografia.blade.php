
<div class="bloco">
    <h2>Questionário de Tomografia Computadorizada</h2>
    <div style="padding: 10px">Esse questionário é obrigatório e deve ser preenchido por completo. São informações que auxiliarão a equipe médica na condução e analise de seu
        exame e de sua segurança. A finalidade do meio de contraste é permitir uma melhor visualização dos órgãos internos, tornando mais nítidas as doenças que porventura
        existem. As reações adversas, podem ocorrer com intensidades variáveis, desde leve, moderada, e até graves. <b>Mulheres grávidas ou com suspeita de gravidez ou
            amamentando devem informar ao médico, operador do equipamento ou enfermagem antes da realização do exame.</b>
    </div>

    <div class="bloco" style="margin: 0 10px">
        <table border="0" style="width: 100%;" cellspacing="1" cellpadding="2">
            <tr class="<?php echo $cor_list = (@$cor_list == "even") ? "odd" : "even";?>">
                <th colspan="3"><b>Utilização de contraste:</b></th>
            </tr>

            @foreach(\App\AnamnesePerguntas::FormularioTomografia(1) AS $row)
                <tr class="<?php echo $cor_list = (@$cor_list == "even") ? "odd" : "even";?>">
                    <td width="86%" style="line-height: 16px">{!! $row !!}</td>
                    <td width="7%" style="line-height: 16px" nowrap>
                        <span class="quadrado">&nbsp;</span>  Sim<br>
                    </td>
                    <td width="7%" style="line-height: 16px" nowrap>
                        <span class="quadrado">&nbsp;</span>  Não
                    </td>
                </tr>
            @endforeach
            @foreach(\App\AnamnesePerguntas::FormularioTomografia(2) AS $row)
                <tr class="<?php echo $cor_list = (@$cor_list == "even") ? "odd" : "even";?>">
                    <td style="line-height: 16px" colspan="3">{!! $row !!}</td>
                </tr>
            @endforeach
        </table>
    </div>

    <div class="bloco" style="margin: 10px 10px">
        <table border="0" style="width: 100%;" cellspacing="1" cellpadding="2">
            <tr>
                <th colspan="3" class="title"><b>Se for mulher</b></th>
            </tr>

            @foreach(\App\AnamnesePerguntas::FormularioTomografia(3) AS $row)
                <tr class="<?php echo $cor_list = (@$cor_list == "even") ? "odd" : "even";?>">
                    <td width="86%" style="line-height: 16px">{!! $row !!}</td>
                    <td width="7%" style="line-height: 16px" nowrap>
                        <span class="quadrado">&nbsp;</span>  Sim<br>
                    </td>
                    <td width="7%" style="line-height: 16px" nowrap>
                        <span class="quadrado">&nbsp;</span>  Não
                    </td>
                </tr>
            @endforeach
            <tr class="<?php echo $cor_list = (@$cor_list == "even") ? "odd" : "even";?>">
                <td colspan="3" style="line-height: 16px">
                    Número de gestações: _______ Número de abortos: _____ Data da última menstruação: _____/_____/________
                </td>

            </tr>
        </table>
    </div>

    <div class="bloco" style="margin: 10px 10px">
        <table border="0" style="width: 100%;" cellspacing="1" cellpadding="2">
            <tr>
                <th  class="title"><b>Assinale com um X se está em uso de algum medicamento</b></th>
            </tr>

            @foreach(\App\AnamnesePerguntas::FormularioTomografia(4) AS $row)
                <tr class="<?php echo $cor_list = (@$cor_list == "even") ? "odd" : "even";?>">
                    <td width="100%" style="line-height: 16px"><span class="quadrado">&nbsp;</span>  {!! $row !!}</td>
                </tr>
            @endforeach
        </table>
    </div>


</div>

