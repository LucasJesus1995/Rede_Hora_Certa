<table border="0" style="width: 100%;" cellspacing="1" cellpadding="2">
    <tr>
        <td width="100%">
            <table border="0" style="width: 100%;" cellspacing="1" cellpadding="7">
                <tr>
                    <td width="16,5%">
                        ( &nbsp;&nbsp;&nbsp; ) <strong>1ª Consulta</strong>
                    </td>
                    <td width="16,5%">
                        ( &nbsp;&nbsp;&nbsp; ) <strong>Biometria</strong>
                    </td>
                    <td width="16,5%">
                        ( &nbsp;&nbsp;&nbsp; ) <strong>Retorno</strong>
                    </td>
                    <td width="16,5%">
                        ( &nbsp;&nbsp;&nbsp; ) <strong>____ PO Pterígio</strong>
                    </td>
                    <td width="16,5%">
                        ( &nbsp;&nbsp;&nbsp; ) <strong>____ PO Yag</strong>
                    </td>
                    <td width="16,5%">
                        ( &nbsp;&nbsp;&nbsp; ) <strong>____ PO Catarata</strong>
                    </td>
                </tr>
            </table>
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
                    <strong>OD: </strong>__________
                    <strong>OD: </strong>__________
                    <strong>S/C: </strong>_________
                </div>
            </td>
            <td width="14%">
                <div style="line-height: 17px;">
                    <strong>OE: </strong>___________<br/>
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
                    <strong>x </strong>___________<br/>
                    <strong>x </strong>___________
                </div>
            </td>
            <td width="14%">
                <div style="line-height: 17px;">
                    <strong>x </strong>___________ <br/>
                    <strong>x </strong>___________
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

<table border="0" style="width: 100%;" cellspacing="0" cellpadding="0">
    <tr>
        <td width="50%" style="padding-left: 0 !important;">
            <div class="bloco" style="margin-top: 0">
                <h2>CERATOMETRIA (KT)</h2>
                <div style="line-height: 17px; padding-left: 5px; text-align: center">
                    <strong>OD: </strong>_________x_________<br/>
                    <strong>OE: </strong>_________x_________
                </div>
            </div>
        </td>
        <td width="50%" style="padding-right: 0 !important;">
            <div class="bloco" style="margin-top: 0">
                <h2>BIOMETRIA</h2>
                <div style="line-height: 17px; padding-left: 5px; text-align: center">
                    <strong>OD: </strong>___________________<br/>
                    <strong>OE: </strong>___________________
                </div>
            </div>
        </td>
    </tr>
</table>

<div class="bloco" style="margin-top: 0">
    <h2>HIPÓTESE DIAGNÓSTICA</h2>
    <div style="line-height: 17px; padding-left: 5px; text-align: justify">
        ___________________________________________________________________________________________________________________________________<br/>
        ___________________________________________________________________________________________________________________________________
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
    <h2>AGENDAMENTO - CIES</h2>
    <table border="0" style="width: 100%; " cellspacing="1" cellpadding="4">
        <tr>
            <td width="30%" style="line-height: 17px">
                <strong style="margin-right: 20px">( &nbsp;&nbsp; ) CATARATA</strong> ( &nbsp;&nbsp; ) OD &nbsp;&nbsp;&nbsp;&nbsp; ( &nbsp;&nbsp; ) OE
                <br/><strong style="margin-right: 29px">( &nbsp;&nbsp; ) PTERÍGIO</strong> ( &nbsp;&nbsp; ) OD &nbsp;&nbsp;&nbsp;&nbsp; ( &nbsp;&nbsp; ) OE
            </td>
            <td width="37%" style="line-height: 17px;  border-left: 1px solid #000">
                <strong style="margin-right: 78px">( &nbsp;&nbsp; ) YAG LASER</strong> ( &nbsp;&nbsp; ) OD &nbsp;&nbsp;&nbsp;&nbsp; ( &nbsp;&nbsp; ) OE
                <br/><strong style="margin-right: 70px">( &nbsp;&nbsp; ) USG OCULAR</strong> ( &nbsp;&nbsp; ) OD &nbsp;&nbsp;&nbsp;&nbsp; ( &nbsp;&nbsp; ) OE
            </td>
            <td width="33%" style="line-height: 17px; border-left: 1px solid #000">
                <strong style="margin-right: 78px">( &nbsp;&nbsp; ) EXAMES LABORATORIAIS</strong>
                <br/><strong style="margin-right: 20px">( &nbsp;&nbsp; ) BIOMETRIA</strong> ( &nbsp;&nbsp; ) OD &nbsp;&nbsp;&nbsp;&nbsp; ( &nbsp;&nbsp; ) OE
            </td>
        </tr>
    </table>
</div>

<table width="100%" cellspacing="0" cellpadding="0">
    <tr>
        <td width="100%" style="padding: 0 !important;">
            <div class="bloco" style="margin-top: 0">
                <h2>RERENCIAMENTO SUS</h2>
                <table border="0" style="width: 100%;" cellspacing="0" cellpadding="0">
                    <tr class="" style="text-align: justify">
                        <td><strong>Encaminhado para:</strong>
                            ( &nbsp;&nbsp; ) Glaucoma&nbsp;&nbsp;&nbsp;&nbsp;
                            ( &nbsp;&nbsp; ) Retina&nbsp;&nbsp;&nbsp;&nbsp;
                            ( &nbsp;&nbsp; ) Plástica Ocular&nbsp;&nbsp;&nbsp;&nbsp;
                            ( &nbsp;&nbsp; ) Córnea&nbsp;&nbsp;&nbsp;&nbsp;
                            ( &nbsp;&nbsp; ) Outros:_______________________________________________
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: justify; line-height: 25px">
                            <strong>Justificativa (obrigatória): ____________________________________________________________________________________________________________</strong>
                            <br />___________________________________________________________________________________________________________________________________
                        </td>
                    </tr>

                </table>
            </div>
        </td>
    </tr>
</table>

<div class="align-center" style=" line-height: 15px; margin-top: 35px">
    ____________________________________________<br>
    Assinatura e carimbo
</div>