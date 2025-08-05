@include('elements.layout.pdf.kit.header', array('agenda'=>$agenda->id, 'box' => false, 'exibe_linha' => true, 'logo_drsaude' => true))

<div class="bloco" style=" margin: 5px;">
    <h2>Ficha de atendimento inicial</h2>

    <div class="margin10">
        <div class="bloco line-height-20">
            <table class="table-kit-impressao" width="100%">
                <tr>
                    <th class="sub-title" colspan="7">Hipótese diagnóstica / CID:</th>
                </tr>
                @foreach(\App\AnamnesePerguntas::FormularioVascular(3) AS $k => $rows)
                    <tr class="<?php echo $cor_list = (@$cor_list == "even") ? "odd" : "even";?>">
                        <td>{!! $k !!}</td>
                        @foreach($rows AS $row)
                            <td>
                                @if($row != null)
                                    <span class="quadrado">&nbsp;</span> {!! $row !!}
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
                <tr>
                    <td colspan="7">
                        {!! App\Http\Helpers\Util::StrPadRight("<b>Outros</b>&nbsp;", 137, "_"); !!}
                    </td>
                </tr>
            </table>
        </div>

        <div class="bloco line-height-20">
            <table class="table-kit-impressao" width="100%">
                <tr>
                    <th class="sub-title">Conduta</th>
                </tr>
                <tr>
                    <td><strong>Orientações: ________________________________________________________________________________________________________</strong></td>
                </tr>
                <tr>
                    <td><strong>Medicações: _________________________________________________________________________________________________________</strong></td>
                </tr>
            </table>
        </div>

        <div class="bloco line-height-20">
            <table class="table-kit-impressao" width="100%">
                <tr>
                    <th class="sub-title">Agendamento - CIES</th>
                </tr>
                <tr>
                    <td>
                        Tratamento esclerosante não estético de varizes dos membros inferiores &nbsp;(&nbsp;&nbsp;&nbsp;&nbsp;) Unilaterial &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(&nbsp;&nbsp;&nbsp;&nbsp;) Bilateral
                    </td>
                </tr>
            </table>
        </div>


        <div class="bloco line-height-20">
            <table class="table-kit-impressao" width="100%">
                <tr>
                    <th  class="sub-title" colspan="2">REFERENCIAMENTO SUS</th>
                </tr>
                <tr>
                    <td colspan="2">
                        <strong>Encaminhado para: </strong> (&nbsp;&nbsp;&nbsp;&nbsp;) Tratamento cirúrgico de Varizes dos membros inferiores CID I83.9<br />
                        Justificativa (obrigatória): ________________________________________________________________________________________________________<br />
                        _____________________________________________________________________________________________________________________________
                    </td>
                </tr>
                <tr>
                    <td colspan="2" ><br />
                        USG Doppler: __________________________________________________________________________ &nbsp;&nbsp;data da realização _____/_____/___________<br/>
                        Descrição do laudo: ____________________________________________________________________________________________________________<br />
                        _____________________________________________________________________________________________________________________________
                    </td>
                </tr>
                <tr>
                    <td colspan="2" ><br />
                        (&nbsp;&nbsp;&nbsp;&nbsp;) Outro ______________________________________________________________________________________________ &nbsp;&nbsp;CID:  __________-_____<br/>
                        Justificativa (obrigatória): ________________________________________________________________________________________________________<br />
                        _____________________________________________________________________________________________________________________________
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><br />
                        Exames: _____________________________________________________________________________________________________________________<br />
                        Descrição: ____________________________________________________________________________________________________________________<br />
                        _____________________________________________________________________________________________________________________________
                    </td>
                </tr>
            </table>
        </div>

        <p>(&nbsp;&nbsp;&nbsp;&nbsp;) Alta</p>
        <p>Alta com tratamento clínico para o CID correspondente</p>
    </div>
</div>


<div class="align-center" style=" line-height: 18px; margin-top: 50px">
    ____________________________________________<br>
    Assinatura e carimbo
</div>

<div style="position: absolute; bottom: -50px">
    @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id])
</div>

<div style='page-break-before:always;'>

    @include('elements.layout.pdf.kit.header', array('agenda'=>$agenda->id, 'box' => false, 'exibe_linha' => true, 'logo_drsaude' => true))

    <br/><br/><strong style="margin-right: 100px">( &nbsp;&nbsp; ) Retorno</strong> ( &nbsp;&nbsp; ) ____ PO<br/><br/>

    @foreach(['DIAGNÓSTICO','QUEIXAS','EXAME FÍSICO', 'CONDUTA'] AS $row)
        <div class="bloco" style="">
            <h2>{!! $row !!}</h2>
            <table border="0" style="width: 100%;" cellspacing="1" cellpadding="2">
                <tr>
                    <td width="80%">
                        <div style="line-height: 19px; text-align: justify-all" class="">
                            @for( $i = 0; $i< 6; $i++)
                                ____________________________________________________________________________________________________________________________________<br/>
                            @endfor
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <br />
    @endforeach

    <div class="align-center" style=" line-height: 18px; margin-top: 50px">
        ____________________________________________<br>
        Assinatura e carimbo
    </div>

    <div style="position: absolute; bottom: -50px">
        @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id])
    </div>
</div>

