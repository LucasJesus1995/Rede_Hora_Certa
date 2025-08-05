@include('elements.layout.pdf.kit.header', array('agenda'=>$agenda->id, 'box' => false, 'logo_drsaude' => false, 'hidden_logo_header' => true))

<h1 class="title">CHECK LIST DE PROCEDIMENTO SEGURO</h1>

<div class="">
    <div class="bloco " style="margin: 0; margin-top: 10px;">
        <h2 style="text-align: left">&nbsp; <!--CHECK LIST DE CIRURGIA SEGURA --></h2>
        <div style="line-height: 22px">
            <table width="100%" class="">
                <tr>
                    <td width="25%" class="line-height-16">
                        <p><strong>Paciente confirmou</strong></p>
                        @foreach(\App\Http\Helpers\Anamnese::checkListCirurgia(1) AS $row)
                            <p style="margin-left: 5px">(&nbsp;&nbsp;&nbsp;) {!! $row !!}</p>
                        @endforeach
                    </td>
                    <td width="25%" class="line-height-16">
                        <p><strong>Demarcação do Sítio</strong></p>
                        @foreach(\App\Http\Helpers\Anamnese::checkListCirurgia(2) AS $row)
                            <p style="margin-left: 5px">(&nbsp;&nbsp;&nbsp;) {!! $row !!}</p>
                        @endforeach
                    </td>
                    <td width="25%" class="line-height-16">
                        <p><strong>Paciente tem Alergias Conhecidas</strong></p>
                        @foreach(\App\Http\Helpers\Anamnese::checkListCirurgia(2) AS $row)
                            <p style="margin-left: 5px">(&nbsp;&nbsp;&nbsp;) {!! $row !!}</p>
                        @endforeach
                    </td>
                    <td width="25%" class="line-height-16">
                        <p><strong>Testagem de Equipamento</strong></p>
                        @foreach(\App\Http\Helpers\Anamnese::checkListCirurgia(3) AS $row)
                            <p style="margin-left: 5px">(&nbsp;&nbsp;&nbsp;) {!! $row !!}</p>
                        @endforeach
                    </td>
                </tr>
            </table>

        </div>
    </div>

    <div class="bloco " style="margin: 0; margin-top: 10px;">
        <h2 style="text-align: left">&nbsp; <!--TIME OUT (antes de incisão)--></h2>
        <div style="margin: 10px 5px; line-height: 22px">
            <p>(&nbsp;&nbsp;&nbsp;) Confirmar se toda equipe se apresentou pelo nome e função</p>
            <table width="100%" style="margin-top: 5px">
                <tr>
                    <td width="50%" class="line-height-16">
                        <p><strong>Cirurgião, enfermagem confirmaram verbalmente</strong></p>
                        <p style="margin-left: 5px">(&nbsp;&nbsp;&nbsp;) Paciente</p>
                        <p style="margin-left: 5px">(&nbsp;&nbsp;&nbsp;) Local Cirúrgico</p>
                        <p style="margin-left: 5px">(&nbsp;&nbsp;&nbsp;) Sim Qual? ______________________</p>
                        <p style="margin-left: 5px">(&nbsp;&nbsp;&nbsp;) Não Aplicável</p>
                    </td>
                    <td width="50%" class="line-height-16">
                        <p><strong>Tricotomia realizada</strong></p>
                        <p style="margin-left: 5px">(&nbsp;&nbsp;&nbsp;) Sim &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(&nbsp;&nbsp;&nbsp;) Não</p>
                        <p style="margin-left: 5px">(&nbsp;&nbsp;&nbsp;) Não Aplicável</p>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="bloco " style="margin: 0; margin-top: 10px;">
        <h2 style="text-align: left">&nbsp; <!--SIGN OUT (antes do paciente sair da sala de cirurgia)--></h2>
        <div style="margin: 10px 5px; line-height: 22px">
            <table width="100%" style="margin-top: 5px">
                <tr>
                    <td width="50%" class="line-height-16">
                        <p><strong>Enfermeiro/Técnico confirma verbalmente com a equipe</strong></p>
                        <p style="margin-left: 5px">(&nbsp;&nbsp;&nbsp;) Nome de procedimento realizado</p>
                        <p style="margin-left: 5px">(&nbsp;&nbsp;&nbsp;) Contagem de instrumentais, gases, compressas e agulhas estão corretas</p>
                        <p style="margin-left: 5px">(&nbsp;&nbsp;&nbsp;) Amostra patológica está identificada e registrada?</p>
                        <p style="margin-left: 5px">(&nbsp;&nbsp;&nbsp;) Paciente encaminhado para recuperação anestésica</p>
                    </td>
                </tr>
            </table>
        </div>
    </div>

</div>

<p style="margin-top: 70px">@include('elements.layout.kit.cirurgico.assinatura-carimbo-enfermagem')</p>


<div style="position: absolute; bottom: -50px">
    @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id])
</div>