<div class="bloco">
    <h2>Questionário de Ressonância Magnética</h2>
    <div style="padding: 10px">Esse questionamento é obrigatório e deve ser preenchido por completo. São informações que auxiliarão a equipe médica na condução e analise de
        seu exame e de sua
        segurança. Tais informações permanecerão em sigilo médico.
    </div>

    <div class="bloco" style="margin: 0 10px">
        <table border="0" style="width: 100%;" cellspacing="1" cellpadding="2">
            <tr class="<?php echo $cor_list = (@$cor_list == "even") ? "odd" : "even";?>">
                <th width="86%"><b>Segurança:</b></th>
                <td width="7%">
                    <span class="quadrado">&nbsp;</span> Sim
                </td>
                <td width="7%">
                    <span class="quadrado">&nbsp;</span> Não
                </td>
            </tr>

            @foreach(\App\AnamnesePerguntas::FormularioRessonancia(1) AS $row)
                <tr class="<?php echo $cor_list = (@$cor_list == "even") ? "odd" : "even";?>">
                    <td style="line-height: 16px">{!! $row !!}</td>
                    <td style="line-height: 16px">
                        <span class="quadrado">&nbsp;</span> Sim<br>
                    </td>
                    <td style="line-height: 16px">
                        <span class="quadrado">&nbsp;</span> Não
                    </td>
                </tr>
            @endforeach
        </table>
    </div>

    <div class="bloco" style="margin: 10px 10px">
        <table border="0" style="width: 100%;" cellspacing="1" cellpadding="2">
            <tr>
                <th colspan="3" class="title"><b>Se for mulher</b></th>
            </tr>

            @foreach(\App\AnamnesePerguntas::FormularioRessonancia(2) AS $row)
                <tr class="<?php echo $cor_list = (@$cor_list == "even") ? "odd" : "even";?>">
                    <td width="86%" style="line-height: 16px">{!! $row !!}</td>
                    <td width="7%" style="line-height: 16px">
                        <span class="quadrado">&nbsp;</span> Sim<br>
                    </td>
                    <td width="7%" style="line-height: 16px">
                        <span class="quadrado">&nbsp;</span> Não
                    </td>
                </tr>
            @endforeach
            <tr class="<?php echo $cor_list = (@$cor_list == "even") ? "odd" : "even";?>">
                <td colspan="3" style="line-height: 16px">
                    Número de gestações____ Número de abortos____
                    <br/>Data de última menstruação: ____/____/____
                </td>

            </tr>
        </table>
    </div>

    <div class="bloco" style="margin: 10px 10px">
        <table border="0" style="width: 100%;" cellspacing="1" cellpadding="2">
            <tr>
                <th width="86%" colspan="3" class="title"><b>Se for homem</b></th>
            </tr>

            @foreach(\App\AnamnesePerguntas::FormularioRessonancia(3) AS $row)
                <tr class="<?php echo $cor_list = (@$cor_list == "even") ? "odd" : "even";?>">
                    <td width=86%" style="line-height: 16px">{!! $row !!}</td>
                    <td width="7%" style="line-height: 16px">
                        <span class="quadrado">&nbsp;</span> Sim<br>
                    </td>
                    <td width="7%" style="line-height: 16px">
                        <span class="quadrado">&nbsp;</span> Não
                    </td>
                </tr>
            @endforeach
            <tr class="<?php echo $cor_list = (@$cor_list == "even") ? "odd" : "even";?>">
                <td colspan="3" style="line-height: 16px">
                    Valor da PSA____________
                    <br/>Data: ____/____/____
                </td>

            </tr>
        </table>
    </div>

</div>

