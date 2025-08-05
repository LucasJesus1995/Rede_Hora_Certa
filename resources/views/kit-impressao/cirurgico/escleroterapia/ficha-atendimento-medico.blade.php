<div style='page-break-before:always;'>
    @include('elements.layout.pdf.kit.header', array('agenda'=>$agenda->id, 'box' => false, 'logo_drsaude' => false, 'hidden_logo_header' => true))
    <div class="bloco">
        <h2>FICHA DE ATENDIMENTO MÉDICO</h2>

        <div style="margin: 5px">

            <table border="0" style="width: 100%;" cellspacing="1" cellpadding="2">
                <tr>
                    <td style="width: 50%;">
                        @foreach(['QUEIXA PRINCIPAL E DURAÇÃO','ANTECEDENTES PESSOAIS','EXAME FÍSICO', 'DIAGNÓSTICO PRINCIPAL'] AS $row)
                            <div class="bloco" style="">
                                <h2 style="text-align: left">{!! $row !!}</h2>
                                <div style="margin: 22px 5px; line-height: 22px" class="box-line-data">
                                    <span class="line-data-text italic"></span>
                                    @if($row == 'DIAGNÓSTICO PRINCIPAL')
                                        <hr/>
                                        @if($agenda->linha_cuidado == 47)
                                            Urologia - Esterilização
                                        @endif
                                        @if(in_array($sub_especialidade, [1,2]))
                                            Catarata
                                        @else
                                            @if($agenda->linha_cuidado == 45)
                                                Vascular – Varizes dos MMII
                                            @endif
                                        @endif
                                    @else
                                        <hr/>
                                        <br/>
                                    @endif
                                    <hr/>
                                    <br/>
                                    <hr/>
                                    <br/>
                                    <hr/>
                                </div>
                            </div>
                        @endforeach
                    </td>
                    <td style="width: 50%;">
                        @foreach(['HISTÓRIA PREGRESSA DA MOLÉSTIA ATUAL','ANTECEDENTES FAMILIARES','EXAMES COMPLEMENTARES', 'DIAGNÓSTICOS ASSOCIADOS'] AS $row)
                            <div class="bloco" style="">
                                <h2 style="text-align: left">{!! $row !!}</h2>
                                <div style="margin: 22px 5px; line-height: 22px" class="box-line-data">
                                    <span class="line-data-text italic"></span>
                                    <hr/>
                                    <br/>
                                    <hr/>
                                    <br/>
                                    <hr/>
                                    <br/>
                                    <hr/>
                                </div>
                            </div>
                        @endforeach

                    </td>
                </tr>
            </table>

        </div>

    </div>

    <div style="margin-top: 100px">
        @include('kit-impressao.cirurgico.default.assinaturas.assinatura-carimbo-medico')
    </div>

    <div style="position: absolute; bottom: -50px">
        @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id])
    </div>
</div>