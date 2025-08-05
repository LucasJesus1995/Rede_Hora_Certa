@include('elements.layout.pdf.kit.header', array('agenda'=>$agenda->id, 'box' => false, 'logo_drsaude' => false, 'hidden_logo_header' => true))

<h1 class="title">
    @if(in_array($agenda->linha_cuidado, [9]))
        FICHA DE AVALIAÇÃO MÉDICA
    @else
        FICHA DE INTERNAÇÃO
    @endif
</h1>

<table width="100%">
    <tr>
        <td width="50%">
            <div class="bloco" style="margin: 0; margin-top: 10px;">
                <h2 style="text-align: left">Queixa Principal e Duração</h2>
                <div style="margin: 22px 5px; line-height: 22px" class="box-line-data">
                    <span class="line-data-text italic"></span>
                    <hr/>
                    <br/>
                    <hr/>
                </div>
            </div>
        </td>
        <td width="50%">
            <div class="bloco" style="margin: 0; margin-top: 10px;">
                <h2 style="text-align: left">História Pregressa da Moléstia Atual:</h2>
                <div style="margin: 22px 5px; line-height: 22px" class="box-line-data">
                    <span class="line-data-text italic"></span>
                    <hr/>
                    <br/>
                    <hr/>
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <td width="50%">
            <div class="bloco" style="margin: 0; margin-top: 10px;">
                <h2 style="text-align: left">Antecedentes Pessoais:</h2>
                <div style="margin: 22px 5px; line-height: 22px" class="box-line-data">
                    <span class="line-data-text italic"></span>
                    <hr/>
                    <br/>
                    <hr/>
                </div>
            </div>
        </td>
        <td width="50%">
            <div class="bloco" style="margin: 0; margin-top: 10px;">
                <h2 style="text-align: left">Antecedentes Familiares:</h2>
                <div style="margin: 22px 5px; line-height: 22px" class="box-line-data">
                    <span class="line-data-text italic"></span>
                    <hr/>
                    <br/>
                    <hr/>
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <td width="50%">
            <div class="bloco" style="margin: 0; margin-top: 10px;">
                <h2 style="text-align: left">Exame Físico:</h2>
                <div style="margin: 22px 5px; line-height: 22px" class="box-line-data">
                    <span class="line-data-text italic"></span>
                    <hr/>
                    <br/>
                    <hr/>
                </div>
            </div>
        </td>
        <td width="50%">
            <div class="bloco" style="margin: 0; margin-top: 10px;">
                <h2 style="text-align: left">Exames Complementares:</h2>
                <div style="margin: 22px 5px; line-height: 22px" class="box-line-data">
                    <span class="line-data-text italic"></span>
                    <hr/>
                    <br/>
                    <hr/>
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div class="bloco" style="margin: 0; margin-top: 10px;">
                <h2 style="text-align: left">Diagnóstico Principal:</h2>
                <div style="margin: 22px 5px; line-height: 22px" class="box-line-data">
                    <span class="line-data-text italic">
                        @if(in_array($sub_especialidade, [1]))
                            Catarata
                        @endif
                        @if(in_array($sub_especialidade, [2]))
                            Pterígio
                        @endif
                        @if(in_array($sub_especialidade, [4]))
                            Hérnia
                        @endif
                        @if(in_array($sub_especialidade, [5]))
                            Pequena Cirurgia
                        @endif
                        @if(in_array($agenda->linha_cuidado, [9,49]))
                            Vascular
                        @endif
                        @if(in_array($agenda->linha_cuidado, [47]))
                            Z30.2 - Esterilização
                        @endif
                    </span>
                    <hr/>
                    <br/>
                    <hr/>
                </div>
            </div>
        </td>
        <td>
            <div class="bloco" style="margin: 0; margin-top: 10px;">
                <h2 style="text-align: left">Diagnósticos Associados:</h2>
                <div style="margin: 22px 5px; line-height: 22px; height: 24px">

                </div>
            </div>
        </td>
    </tr>

    @if(!in_array($agenda->linha_cuidado, [9]))
        <tr>
            <td colspan="2">

                <h1 class="title">AVALIAÇÃO PRÉ</h1>
                <?php
                $_data[] = "Exames laboratoriais:";
                $_data[] = "Eletrocardiograma:";

                ?>
                @foreach($_data AS $row)
                    <div class="bloco" style="margin: 0; margin-top: 10px;">
                        <h2 style="text-align: left">{!! $row !!}</h2>
                        <div style="margin: 5px; height: 30px">

                        </div>
                    </div>
                @endforeach
                <div class="bloco" style="margin: 0; margin-top: 10px;">
                    <h2 style="text-align: left">Conclusão:</h2>
                    <div style="margin: 5px;  height: 30px; font-style: italic ">
                        Liberado para procedimento

                    </div>
                </div>

            </td>
        </tr>
    @endif
</table>

<p style="margin-top: 70px">@include('elements.layout.kit.cirurgico.assinatura-carimbo-medico')</p>

<div style="position: absolute; bottom: -50px">
    @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id])
</div>