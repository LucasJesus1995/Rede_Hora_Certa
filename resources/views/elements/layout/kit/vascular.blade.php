<?php
$questionario = \App\AnamnesePerguntas::Questionario(9);
?>


<div class="bloco" style=" margin: 5px;">
    <h2>Ficha de atendimento inicial</h2>

    <div class="margin10">
        <table class="table-kit-impressao">
            <tr>
                <th width="30%">Queixa Principal</th>
                <td width="20%">(&nbsp;&nbsp;&nbsp;) varizes</td>
                <td width="50%">(&nbsp;&nbsp;&nbsp;) outros: _____________________________________________________</td>
            </tr>
        </table>

        <div class="bloco line-height-20">
            <table class="table-kit-impressao">
                <tr>
                    <th class="sub-title" colspan="3">Antecedentes pessoais:</th>
                </tr>
                @foreach(\App\AnamnesePerguntas::FormularioVascular(1) AS $row)
                    <tr class="<?php echo $cor_list = (@$cor_list == "even") ? "odd" : "even";?>">
                        <td width="7%"><span class="quadrado">&nbsp;</span> não</td>
                        <td width="7%"><span class="quadrado">&nbsp;</span> sim</td>
                        <td width="86%" style="text-align: justify">{!! $row !!}</td>
                    </tr>
                @endforeach
            </table>
        </div>

        <div class="bloco line-height-20">
            <table class="table-kit-impressao">
                <tr>
                    <th class="sub-title" colspan="2">Exame fisico:</th>
                </tr>
                <tr>
                    <th width="50%">Membro inferior <b>Direito</b></th>
                    <th width="50%">Membro inferior <b>Esquerdo</b></th>
                </tr>
                @foreach(\App\AnamnesePerguntas::FormularioVascular(2) AS $row)
                    <tr class="<?php echo $cor_list = (@$cor_list == "even") ? "odd" : "even";?>">
                        <td><span class="quadrado">&nbsp;</span> {!! $row !!}</td>
                        <td><span class="quadrado">&nbsp;</span> {!! $row !!}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="2">
                        {!! App\Http\Helpers\Util::StrPadRight("<b>Outros</b>&nbsp;", 135, "_"); !!}
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
