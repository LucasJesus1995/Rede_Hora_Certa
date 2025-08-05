<div class="bloco">
    <div class="" style="margin: 10px">
        <table width="100%" cellspacing="0" cellpadding="0">
            @foreach(\App\AnamnesePerguntas::FormularioTomografia(5) AS $row)
                <tr class="<?php echo $cor_list = (@$cor_list == "even") ? "odd" : "even";?>">
                    <td width="600px" style="line-height: 16px; font-weight: bold">> {!! $row !!}</td>
                    <td width="50px" style="line-height: 16px">
                        <span class="quadrado">&nbsp;</span> Sim<br>
                    </td>
                    <td width="50px" style="line-height: 16px">
                        <span class="quadrado">&nbsp;</span> Não
                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="3" style="line-height: 23px">
                    Quais motivos o (a) levaram a procurar o médico? O que está apresentando?
                    <br/>________________________________________________________________________________________________________________________________
                    <br/>________________________________________________________________________________________________________________________________
                    <br/>________________________________________________________________________________________________________________________________
                    <br/>________________________________________________________________________________________________________________________________
                    <br/>________________________________________________________________________________________________________________________________
                    <br/><br/>
                </td>
            </tr>
            @foreach(\App\AnamnesePerguntas::FormularioTomografia(6) AS $row)
                <tr class="<?php echo $cor_list = (@$cor_list == "even") ? "odd" : "even";?>">
                    <td style="line-height: 16px;">{!! $row !!}</td>
                    <td style="line-height: 16px">
                        <span class="quadrado">&nbsp;</span> Sim<br>
                    </td>
                    <td style="line-height: 16px">
                        <span class="quadrado">&nbsp;</span> Não
                    </td>
                </tr>
            @endforeach
        </table>

        <div class="bloco" style="margin-top: 20px">
            <h2>TERMO DE CONSENTIMENTO E RESPONSABILIDADE</h2>
            <p style="padding: 10px; text-align: justify; line-height: 20px;">
                Eu declaro que li e compreendi as informações sobre o exame de Tomografia Computadorizada. Estou ciente dos objetos, riscos e efeitos colaterais deste exame e do meio de contraste que possa ser utilizado para a sua realização, bem como as orientações após a finalização do estudo.
            </p>
            <table width="100%">
                <tr>
                    <td width="33%">
                        Hospital: ____________________________

                    </td>
                    <td width="33%">
                        Convênio:____________________________
                    </td>
                    <td width="33%" class="align-center">
                        _________________________________________<br/>
                        Assinatura do cliente ou responsável
                    </td>
                </tr>
            </table>
        </div>

        <br/><br/>
        <table class="table-border" width="100%" cellpadding="8" cellspacing="0">
            <tr>
                <th width="33%" colspan="2" class="title">ANOTAÇÃO DE ENFERMAGEM</th>
                <th width="33%" class="title">ANOTAÇÕES TÉCNOLOGO</th>
                <th width="33%" class="title" colspan="3">PRESCRIÇÃO MÉDICA</th>
            </tr>
            <tr>
                <td colspan="2">Punção em: (&nbsp;&nbsp;&nbsp;) MSD (&nbsp;&nbsp;&nbsp;) MSE</td>
                <td>Exame:</td>
                <td width="11%">Contrastes</td>
                <td width="11%">Volume</td>
                <td width="11%">Via</td>
            </tr>
            <tr>
                <td colspan="2">Dispositivo utilizado na punção:</td>
                <td style="line-height: 22px">
                    Início: ________________________________
                    <br/>Término: ______________________________
                </td>
                <td>(&nbsp;&nbsp;&nbsp;) Iodado</td>
                <td align="right">ml</td>
                <td nowrap>(&nbsp;&nbsp;&nbsp;) Endovenoso</td>
            </tr>
            <tr>
                <td colspan="2">Contraste: (&nbsp;&nbsp;&nbsp;) Oral (&nbsp;&nbsp;&nbsp;) Endovenoso</td>
                <td>Dificuldade para permanecer imóvel: <br> (&nbsp;&nbsp;&nbsp;) Sim &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(&nbsp;&nbsp;&nbsp;) Não</td>
                <td>(&nbsp;&nbsp;&nbsp;) Gel</td>
                <td align="right">ml</td>
                <td nowrap>(&nbsp;&nbsp;&nbsp;) Oral</td>
            </tr>
            <tr>
                <td colspan="2">Volume injetado:(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;) (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                <td>Foi necessário reposicionar? (&nbsp;&nbsp;&nbsp;) Sim &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(&nbsp;&nbsp;&nbsp;) Não</td>
                <td colspan="3">Horário:</td>
            </tr>
            <tr>
                <td colspan="2">
                    Assinatura, Carimbo e Horário
                    <br/><br/><br/><br/>
                </td>
                <td>Assinatura, Carimbo e Horário</td>
                <td colspan="3">Assinatura, Carimbo</td>
            </tr>
        </table>

    </div>
</div>