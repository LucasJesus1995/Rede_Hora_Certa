@include('elements.layout.pdf.kit.header', array('agenda'=>$agenda->id, 'box' => false, 'exibe_linha' => true,  'logo_drsaude' => true))

<div style="">

    <div class="bloco" style="">
        <h2>Prescrição médica oftalmológica</h2>
        <table border="0" style="width: 100%;" cellspacing="1" cellpadding="2">
            <tr>
                <td width="5%">&nbsp;</td>
                <td width="45%">
                    <div class="bloco" style="margin-top: 10px; text-align: center">
                        <h2>Medicamento</h2>
                        <div style="padding: 40px 10px; text-align: center ">
                            Mydriacyl 1% via ocular em ambos os olhos
                        </div>
                    </div>
                </td>
                <td width="45%">
                    <div class="bloco" style="margin-top: 10px">
                        <h2>Dose</h2>
                        <div style="padding: 33px 10px; text-align: center ">
                            1 gota a cada 15 minutos <br/>
                            Repetir por 2 vezes
                        </div>
                    </div>
                </td>
                <td width="5%">&nbsp;</td>
            </tr>
        </table>

        <div class="align-center" style=" line-height: 15px; margin-top: 35px; margin-bottom: 100px">
            ____________________________________________<br>
            Assinatura e carimbo médico
        </div>

        @for($i = 1; $i <= 2; $i++)
        <table border="0" style="width: 100%;" cellspacing="0" cellpadding="0">
            <tr>
                <td width="5%">&nbsp;</td>
                <td width="45%">
                    <div class="bloco" style="margin-top: 10px">
                        <h2>{!! $i !!}ª Dose</h2>
                        <div style="padding: 30px 10px; text-align: center">

                            Horário ____:____

                        </div>
                    </div>
                </td>
                <td width="45%">
                    <div class="bloco" style="margin-top: 10px">
                        <h2>Assinatura e carimbo</h2>
                        <div style="padding: 30px 10px ">&nbsp;</div>
                    </div>
                </td>
                <td width="5%">&nbsp;</td>
            </tr>
        </table>
        @endfor
    </div>


    <div style="position: absolute; bottom: -50px">
        @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id])
    </div>
</div>

