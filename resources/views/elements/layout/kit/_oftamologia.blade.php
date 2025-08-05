<table border="0" style="width: 100%;" cellspacing="1" cellpadding="2">
    <tr>
        <td width="50%">
            <table border="0" style="width: 100%;" cellspacing="1" cellpadding="7">
                <tr>
                    <td width="33%">
                        ( &nbsp;&nbsp;&nbsp; ) <strong>1ª Consulta</strong>
                    </td>
                    <td width="33%">
                        ( &nbsp;&nbsp;&nbsp; ) <strong>____ PO Catarata</strong>
                    </td>
                    <td width="33%">
                        ( &nbsp;&nbsp;&nbsp; ) <strong>Biometria</strong>
                    </td>
                </tr>
                <tr>
                    <td width="33%">
                        ( &nbsp;&nbsp;&nbsp; ) <strong>Retorno</strong>
                    </td>
                    <td width="33%">
                        ( &nbsp;&nbsp;&nbsp; ) <strong>____ PO Pterígio</strong>
                    </td>
                    <td width="33%">
                        ( &nbsp;&nbsp;&nbsp; ) <strong>____ PO Yag</strong>
                    </td>
                </tr>
            </table>
        </td>
        <td width="50%">
            <div class="bloco">
                <h2>Dilatação Pupilar **</h2>
                <table border="0" style="width: 100%;" cellspacing="1" cellpadding="4">
                    <tr>
                        <td width="20%"></td>
                        <td width="20%">
                            ( &nbsp;&nbsp;&nbsp; ) <strong>OD</strong>
                        </td>
                        <td width="20%">
                            ( &nbsp;&nbsp;&nbsp; ) <strong>OE</strong>
                        </td>
                        <td width="40%">
                            Horário: ___ : ___
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
</table>

<div class="bloco" style="">
    <h2>ANAMNESE</h2>
    <table border="0" style="width: 100%;" cellspacing="1" cellpadding="2">
        <tr>
            <td width="80%">
                <div style="line-height: 17px; padding: 0 0 0 5px" class="">
                    _____________________________________________________________________________________________________<br/>
                    _____________________________________________________________________________________________________<br/>
                    _____________________________________________________________________________________________________
                </div>
            </td>
            <td width="20%">
                <div style=" padding: 0 0 0 5px">
                    <div style="text-align: justify; line-height: 17px;">
                        <strong>AP: </strong>_________________<br/>
                        <strong>AF: </strong>_________________<br/>
                        <strong>AO: </strong>_________________
                    </div>
                </div>
            </td>
        </tr>
    </table>
</div>

<div class="bloco">
    <h2>EXAME OFTALMOLÓGICO</h2>
    <table border="0" style="width: 100%;" cellspacing="1" cellpadding="4">
        <tr>
            <td width="14%">
                <div style="line-height: 17px;">
                    <strong>AV CC: </strong>__________
                </div>
            </td>
            <td width="14%">
                <div style="line-height: 17px;">
                    <strong>OD: </strong>___________<br/>
                    <strong>OE: </strong>___________
                </div>
            </td>
            <td width="14%">&nbsp;</td>
            <th width="14%" style="text-align: right; padding-top: 20px">
                Refração:&nbsp;
            </th>
            <td width="14%">
                <div style="line-height: 17px;">
                    <strong>OD: </strong>___________<br/>
                    <strong>OE: </strong>___________
                </div>
            </td>
            <td width="14%">
                <div style="line-height: 17px;">
                    <strong>DE x </strong>___________<br/>
                    <strong>DE x </strong>___________
                </div>
            </td>
            <td width="14%">
                <div style="line-height: 17px;">
                    <strong>DC </strong>___________ º<br/>
                    <strong>DC </strong>___________ º
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3"></td>
            <td colspan="2">
                <div style="line-height: 17px; padding-left: 60px">
                    <strong>Adição: </strong>__________________
                </div>
            </td>
        </tr>
    </table>
</div>

<table border="0" style="width: 100%; margin-top: -5px" cellspacing="0" cellpadding="0">
    <tr>
        <td width="20%" style="padding: 0 !important;">
            <div class="bloco">
                <h2>Tonometria</h2>
                <div style="line-height: 22px; padding-left: 5px">
                    <strong>OD: </strong>__________ mmHg<br/>
                    <strong>OE: </strong>__________ mmHg
                </div>
            </div>
            <div class="bloco">
                <h2>CATARATA</h2>
                <div style="line-height: 22px; padding-left: 5px">
                    <strong>OD: </strong>__________ <br/>
                    <strong>OE: </strong>__________
                </div>
            </div>
        </td>
        <td width="80%" style="padding: 0 !important;">
            <div class="bloco" style="margin-left: 5px">
                <h2>Biomicroscopia</h2>
                <table border="0" style="width: 100%;" cellspacing="1" cellpadding="6">
                    @foreach(\App\AnamnesePerguntas::FormularioOftamologia(1) AS $row)
                        <tr class="<?php echo $cor_list = (@$cor_list == "even") ? "odd" : "even";?>">
                            <td style="" width="17%">{!! $row !!}</td>
                            <td width="17%" style="line-height: 17px;">
                                ( &nbsp;&nbsp; ) S/Alterações
                            </td>
                            <td width="17%" style="line-height: 17px;">
                                AO _____________
                            </td>
                            <td style="line-height: 17px;">
                                Alterações: _______________________________________
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </td>
    </tr>
</table>

<div class="bloco" style="margin-top: 0">
    <h2>Mapeamento de Retina</h2>
    <div style="line-height: 17px; padding-left: 5px; text-align: justify">
        <strong>OD: </strong>______________________________________________________________________________________________________________________________<br/>
        <strong>OE: </strong>______________________________________________________________________________________________________________________________
    </div>
</div>

<div class="bloco" style="margin-top: 0">
    <h2>CONDUTA</h2>
    <div style="line-height: 17px; padding-left: 5px; text-align: justify-all">
        <strong>Orientações: </strong>_______________________________________________________________________________________________________________________<br/>
        <strong>Medicações: </strong>_______________________________________________________________________________________________________________________<br/>
    </div>
</div>

<div class="bloco" style="margin-top: 0">
    <h2>REGULAÇÃO SMS - JUSTIFICAR MOTIVO DO ENCAMINHAMENTO</h2>
    <div style="line-height: 17px; padding-left: 5px; text-align: justify-all">
        <strong>Hipótese diagnóstica: </strong>_______________________________________________________________________________________________________________<br/>
        __________________________________________________________________________________________________________________________________

    </div>
</div>

<div class="bloco" style="margin-top: 0">
    <h2>AGENDAMENTO - CIES</h2>
    <table border="0" style="width: 100%; " cellspacing="1" cellpadding="4">
        <tr>
            <td width="30%" style="line-height: 17px">
                <strong style="margin-right: 20px">( &nbsp;&nbsp; ) CATARATA</strong> ( &nbsp;&nbsp; ) OD &nbsp;&nbsp;&nbsp;&nbsp; ( &nbsp;&nbsp; ) OE
                <br/><strong style="margin-right: 20px">( &nbsp;&nbsp; ) BIOMETRIA</strong> ( &nbsp;&nbsp; ) OD &nbsp;&nbsp;&nbsp;&nbsp; ( &nbsp;&nbsp; ) OE
                <br/><strong style="margin-right: 29px">( &nbsp;&nbsp; ) PTERÍGIO</strong> ( &nbsp;&nbsp; ) OD &nbsp;&nbsp;&nbsp;&nbsp; ( &nbsp;&nbsp; ) OE
            </td>
            <td width="37%" style="line-height: 17px;  border-left: 1px solid #000">
                <strong style="margin-right: 78px">( &nbsp;&nbsp; ) YAG LASER</strong> ( &nbsp;&nbsp; ) OD &nbsp;&nbsp;&nbsp;&nbsp; ( &nbsp;&nbsp; ) OE
                <br/><strong style="margin-right: 70px">( &nbsp;&nbsp; ) USG OCULAR</strong> ( &nbsp;&nbsp; ) OD &nbsp;&nbsp;&nbsp;&nbsp; ( &nbsp;&nbsp; ) OE
            </td>
            <td width="33%" style="line-height: 17px; border-left: 1px solid #000">
                <strong style="margin-right: 78px">( &nbsp;&nbsp; ) PÓS-OPERATÓRIO</strong>
                <br/><strong style="margin-right: 78px">( &nbsp;&nbsp; ) EXAMES LABORATORIAIS</strong>
            </td>
        </tr>
    </table>
</div>

<table width="100%" cellspacing="0" cellpadding="0">
    <tr>
        <td width="50%" style="padding: 0 !important;">
            <div class="bloco" style="margin-top: 0">
                <h2>LIO</h2>
                <table border="0" style="width: 100%;" cellspacing="0" cellpadding="0">
                    <tr class="even">
                        <td align="center">+18,00</td>
                        <td align="center">+18,50</td>
                        <td align="center">+19,00</td>
                        <td align="center">+19,50</td>
                        <td align="center">+20,00</td>
                        <td align="center">+20,50</td>
                    </tr>
                    <tr class="odd">
                        <td align="center">+21,00</td>
                        <td align="center">+21,50</td>
                        <td align="center">+22,00</td>
                        <td align="center">+22,50</td>
                        <td align="center">+23,00</td>
                        <td align="center">+23,50</td>
                    </tr>
                    <tr class="even">
                        <td align="center">+24,00</td>
                        <td align="center">+24,50</td>
                        <td align="center">+25,00</td>
                        <td align="center">+25,50</td>
                        <td align="center">+26,00</td>
                        <td align="center">+26,50</td>
                    </tr>
                </table>
            </div>

        </td>
        <td width="50%" style="padding: 0 !important; padding-left: 5px !important;">
            <div class="bloco" style="margin-top: 0">
                <h2>BIOMETRIA</h2>
                <table border="0" style="width: 100%; " cellspacing="1" cellpadding="4">
                    <tr>
                        <td style="line-height: 10px;  text-align: center">
                            <strong>OD __________________________________________</strong>

                            <div style="margin: 12px 0 !important;"><strong>OE __________________________________________</strong></div>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
</table>

<table width="100%" cellspacing="0" cellpadding="0">
    <tr>
        <td width="70%" style="padding: 0 !important;">
            <i style="font-size: 8px !important;">** Quando solicitada a dilatação pupilar, será feita conforme indicação médica e marcação do olho a ser realizado.</i>
        </td>
        <td width="30%">
            <div style="text-align: right">
                <strong>RETORNO DIA _____/_____/_________</strong>
            </div>
        </td>
    </tr>
</table>

<div class="align-center" style=" line-height: 15px; margin-top: 35px">
    ____________________________________________<br>
    Assinatura e carimbo
</div>