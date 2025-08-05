<div>
    <br/><br/>
    <div class="bloco">
        <table border="0" style="width: 100%;" cellspacing="1" cellpadding="2">
            @foreach(\App\AnamnesePerguntas::FormularioRessonancia(4) AS $row)
                <tr class="<?php echo $cor_list = (@$cor_list == "even") ? "odd" : "even";?>">
                    <td width="70%" style="line-height: 16px; font-weight: bold">> {!! $row !!}</td>
                    <td width="15%" style="line-height: 16px">
                        <span class="quadrado">&nbsp;</span> Sim<br>
                    </td>
                    <td width="15%" style="line-height: 16px">
                        <span class="quadrado">&nbsp;</span> Não
                    </td>
                </tr>
            @endforeach
        </table>
    </div>

    <br/><br/>
    <div class="bloco">
        <table border="0" style="width: 100%;" cellspacing="1" cellpadding="2">
            <tr>
                <th colspan="3">Utilização de contrate:</th>
            </tr>
            @foreach(\App\AnamnesePerguntas::FormularioRessonancia(5) AS $row)
                <tr class="<?php echo $cor_list = (@$cor_list == "even") ? "odd" : "even";?>">
                    <td width="70%" style="line-height: 16px;">{!! $row !!}</td>
                    <td width="15%" style="line-height: 16px">
                        <span class="quadrado">&nbsp;</span> Sim<br>
                    </td>
                    <td width="15%" style="line-height: 16px">
                        <span class="quadrado">&nbsp;</span> Não
                    </td>
                </tr>
            @endforeach
            @foreach(\App\AnamnesePerguntas::FormularioRessonancia(6) AS $row)
                <tr class="<?php echo $cor_list = (@$cor_list == "even") ? "odd" : "even";?>">
                    <td colspan="3" style="line-height: 16px;">{!! $row !!}</td>
                </tr>
            @endforeach
        </table>
    </div>

    <br/><br/>
    <div>
        <h1>RESSONÂNCIA MAGNÉTICA</h1>
        <div class="bloco">
            <h2>TERMO DE CONSENTIMENTO E RESPONSABILIDADE</h2>
            <p style="padding: 10px; text-align: justify; line-height: 20px;">
                Eu declaro que li e compreendi as informações sobre o exame de Ressonância Magnética. Estou ciente dos objetos, riscos e efeitos colaterais deste exame e
                do meio de contraste que possa ser utilizado para sua realização, bem como as orientações após a finalização do estudo. Em caso de ocorrências graves, por
                favor nos informe para qual hospital gostaria de ser levado e qual é o seu convênio. Autorizo a transmissão das imagens médicas.
            </p>
            <table width="100%">
                <tr>
                    <td width="33%">
                        &nbsp;&nbsp;Hospital: ____________________________
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
        <table class="table-border" width="100%" cellpadding="5" cellspacing="0">
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
                <td style="line-height: 22px">Início: _________________ <br/>Término: _______________</td>
                <td>(&nbsp;&nbsp;&nbsp;) Gadolínio</td>
                <td align="right">ml</td>
                <td nowrap>(&nbsp;&nbsp;&nbsp;) Endovenoso</td>
            </tr>
            <tr>
                <td colspan="2">Contraste: (&nbsp;&nbsp;&nbsp;) oral (&nbsp;&nbsp;&nbsp;) Endovenoso</td>
                <td>Dificuldade para permanecer imóvel: <br> (&nbsp;&nbsp;&nbsp;) Sim &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(&nbsp;&nbsp;&nbsp;) Não</td>
                <td>(&nbsp;&nbsp;&nbsp;) Gel</td>
                <td align="right">ml</td>
                <td nowrap>(&nbsp;&nbsp;&nbsp;) Vaginal</td>
            </tr>
            <tr>
                <td colspan="2">Volume injetado:(&nbsp;&nbsp;&nbsp;) 10ml (&nbsp;&nbsp;&nbsp;) 29ml</td>
                <td>Foi necessário reposicionar? (&nbsp;&nbsp;&nbsp;) Sim &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(&nbsp;&nbsp;&nbsp;) Não</td>
                <td colspan="3">Horário:</td>

            </tr>
            <tr>
                <td width="16.5%">Enfermagem:<br/><br/><br/></td>
                <td width="16.5%">Tecnólogo:</td>
                <td></td>
                <td colspan="3"></td>
            </tr>
        </table>
    </div>
</div>