<div class="bloco">
    <h2>EVOLUÇÃO DE ENFERMAGEM</h2>

    <div class="bloco" style=" margin: 5px;">
        <h2 style="text-align: left">QUEIXAS</h2>
        <div class="padding5">
            <table>
                <tr>
                    @foreach(['Ausente','Tontura', 'Náuseas', 'Visão Turva']  AS $row)
                        <td width="15%">(&nbsp;&nbsp;&nbsp;) &nbsp; {!! $row !!}</td>
                    @endforeach
                    <td width="40%">&nbsp;</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="bloco" style=" margin: 5px;">
        <h2 style="text-align: left">Submetido</h2>
        <div class="padding5">
            <table>
                <tr>
                    @foreach(['Endoscopia Digestiva Alta','Endoscopia Digestiva Baixa ']  AS $row)
                        <td width="30%">(&nbsp;&nbsp;&nbsp;) &nbsp; {!! $row !!}</td>
                    @endforeach
                    <td width="40%">&nbsp;</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="bloco" style=" margin: 5px;">
        <h2 style="text-align: left">Estado Geral</h2>
        <div class="padding5">
            <table>
                <tr>
                    @foreach(['BEG','REG', 'PEG']  AS $row)
                        <td width="15%">(&nbsp;&nbsp;&nbsp;) &nbsp; {!! $row !!}</td>
                    @endforeach
                    <td width="55%" nowrap>Outros: _______________________________________________________________</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="bloco" style=" margin: 5px;">
        <h2 style="text-align: left">Percepção/Orientação/Atenção</h2>
        <div class="padding5">
            <table>
                <tr>
                    @foreach(['Consciente','Sonolento', 'Cooperativo', 'Não cooperativo']  AS $row)
                        <td width="15%">(&nbsp;&nbsp;&nbsp;) &nbsp; {!! $row !!}</td>
                    @endforeach
                    <td width="40%">&nbsp;</td>
                </tr>
                <tr class="odd">
                    @foreach(['Orientado','Desorientado', 'Agitado', 'Confuso']  AS $row)
                        <td width="15%">(&nbsp;&nbsp;&nbsp;) &nbsp; {!! $row !!}</td>
                    @endforeach
                    <td width="40%">&nbsp;</td>
                </tr>
            </table>
        </div>
    </div>

    <table style="border-spacing: 0 !important;">
        <tr>
            <td width="50%" style="padding: 0 !important;">
                <div class="bloco" style=" margin: 5px;">
                    <h2 style="text-align: left">Sob Efeito de Sedação</h2>
                    <div class="padding5">
                        <table style="border-spacing: 0 !important;">
                            <tr>
                                @foreach(['Sim','Não']  AS $row)
                                    <td width="30%">(&nbsp;&nbsp;&nbsp;) &nbsp; {!! $row !!}</td>
                                @endforeach
                                <td width="40%">&nbsp;</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </td>
            <td width="50%" style="padding: 0 !important;">
                <div class="bloco" style=" margin: 5px;">
                    <h2 style="text-align: left">Uso Antagonista</h2>
                    <div class="padding5">
                        <table>
                            <tr>
                                @foreach(['Sim','Não']  AS $row)
                                    <td width="30%">(&nbsp;&nbsp;&nbsp;) &nbsp; {!! $row !!}</td>
                                @endforeach
                                <td width="40%">&nbsp;</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <div class="bloco" style=" margin: 5px;">
        <h2 style="text-align: left">Eliminação Intestinal</h2>
        <div class="padding5">
            <table>
                <tr>
                    @foreach(['Diária','Irregular', '____ dias sem', 'Evacuar Espontânea']  AS $row)
                        <td width="25%">(&nbsp;&nbsp;&nbsp;) &nbsp; {!! $row !!}</td>
                    @endforeach
                </tr>
            </table>
        </div>
    </div>

    <div class="bloco" style=" margin: 5px;">
        <h2 style="text-align: left">Colostomia</h2>
        <div class="padding5">
            <table>
                <tr>
                    @foreach(['Sim','Não', 'Funcionante', 'Não Funcionante']  AS $row)
                        <td width="25%">(&nbsp;&nbsp;&nbsp;) &nbsp; {!! $row !!}</td>
                    @endforeach
                </tr>
            </table>
        </div>
    </div>

    <table style="border-spacing: 0 !important;">
        <tr>
            <td width="50%" style="padding: 0 !important;">
                <div class="bloco" style=" margin: 5px;">
                    <h2 style="text-align: left">Recebendo Alta</h2>
                    <div class="padding5">
                        <table style="border-spacing: 0 !important;">
                            <tr>
                                @foreach(['Sem queixas','Queixa de dor Abdominal']  AS $row)
                                    <td width="50%">(&nbsp;&nbsp;&nbsp;) &nbsp; {!! $row !!}</td>
                                @endforeach
                            </tr>
                        </table>
                    </div>
                </div>
            </td>
            <td width="50%" style="padding: 0 !important;">
                <div class="bloco" style=" margin: 5px;">
                    <h2 style="text-align: left">Realizado Orientação</h2>
                    <div class="padding5">
                        <table>
                            <tr>
                                @foreach(['Sim','Não']  AS $row)
                                    <td width="30%">(&nbsp;&nbsp;&nbsp;) &nbsp; {!! $row !!}</td>
                                @endforeach
                                <td width="40%"></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <div class="bloco" style=" margin: 5px;">
        <h2 style="text-align: left">Destino do paciente</h2>
        <div class="padding5">
            <table>
                <tr>
                    @foreach(['Alta pra Residência ','Encaminhado para UBS/AMA', 'SAMU', 'Remarcação _____/_____/________ Horário:  _______:______']  AS $row)
                        <td width="" nowrap>(&nbsp;&nbsp;&nbsp;) &nbsp; {!! $row !!}</td>
                    @endforeach
                </tr>
            </table>
        </div>
    </div>

    <table width="100%" style="margin-top: 60px">
        <tr>
            <td width="50%" align="center">
                &nbsp;
            </td>
            <td width="50%">
                <div style="text-align: center">
                    <div style="margin: 0 auto; border-bottom: 1px #000 dashed; width: 300px; " />
                    ASSINATURA E CARIMBO
                </div>
            </td>
        </tr>
    </table>
</div>

<div class="bloco" style="padding-bottom: 15px">
    <h2 style="text-align: left">Intercorrência:</h2>
    <hr style="margin-top: 22px"/>
    <hr style="margin-top: 20px"/>
    <hr style="margin-top: 20px"/>
</div>

<table width="100%" style="margin-top: 70px">
    <tr>
        <td width="50%" align="center">
            &nbsp;
        </td>
        <td width="50%">
            <div style="text-align: center">
                <div style="margin: 0 auto; border-bottom: 1px #000 dashed; width: 300px; " />
                ASSINATURA E CARIMBO
            </div>
        </td>
    </tr>
</table>