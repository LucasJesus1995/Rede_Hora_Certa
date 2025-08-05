
<div class="bloco">
    <h2>Cirurgias</h2>
    <table border="0" style="width: 100%;" cellspacing="1" cellpadding="2">
        @foreach(\App\AnamnesePerguntas::FormularioMamografia(1) AS $row)
            <tr class="<?php echo $cor_list = (@$cor_list == "even") ? "odd" : "even";?>">
                <td width="66%">{!! $row !!}</td>
                <td width="7%">
                    <span class="quadrado">&nbsp;</span> Sim
                </td>
                <td width="7%">
                    <span class="quadrado">&nbsp;</span>  Não
                </td>
                <td width="20%" align="right">
                    Tempo _________________
                </td>
            </tr>
        @endforeach
    </table>
</div>

<div class="bloco">
    <h2>Outras alterações</h2>
    <table border="0" style="width: 100%;" cellspacing="1" cellpadding="2">
        @foreach(\App\AnamnesePerguntas::FormularioMamografia(2) AS $row)
            <tr class="<?php echo $cor_list = (@$cor_list == "even") ? "odd" : "even";?>">
                <td width="66%">{!! $row !!}</td>
                <td width="7%">
                    <span class="quadrado">&nbsp;</span> Sim
                </td>
                <td width="7%">
                    <span class="quadrado">&nbsp;</span>  Não
                </td>
                <td width="20%" align="right">
                    Tempo _________________
                </td>
            </tr>
        @endforeach
    </table>
</div>

<div class="bloco">
    <h2>Localizador de lesões de pele</h2>
    <div style="text-align: center; padding: 5px">
        <img src="src/image/mamografia.png" width="320px" />
    </div>
    <table border="0" style="width: 100%; margin: 20px" cellspacing="1" cellpadding="2">
        <tr class="">
            <th width="25%"><b>Verruga</b></th>
            <th width="25%"><b>Pinta</b></th>
            <th width="25%"><b>Cicatriz</b></th>
            <th width="25%"><b>Nódulo/ massa palpável</b></th>
        </tr>
    </table>
</div>

<div class="bloco">
    <h2>FICHA E QUESTIONÁRIO DE MAMOGRAFIA</h2>
    <table border="0" style="width: 100%;" cellspacing="1" cellpadding="2">
        <tr>
            <td colspan="12"><b>HISTÓRICO MENSTRUAL</b></td>
        </tr>
        <tr class="odd">
            <td colspan="6">Ainda mestrua</td>
            <td colspan="2"><span class="quadrado">&nbsp;</span> Sim</td>
            <td colspan="2"><span class="quadrado">&nbsp;</span> Não</td>
            <td colspan="2"></td>
        </tr>
        <tr class="">
            <td colspan="6">Data da última menstruação</td>
            <td colspan="4">_____/_____/_________</td>
            <td colspan="2"><span class="quadrado">&nbsp;</span> Não sabe</td>
        </tr>
        <tr class="odd">
            <td colspan="6">Idade da menopausa</td>
            <td colspan="4">____________________</td>
            <td colspan="2"><span class="quadrado">&nbsp;</span> Não lembra</td>
        </tr>
        <tr>
            <td colspan="6">Usa hormônio/remédio para tratar menopausa?</td>
            <td colspan="2"><span class="quadrado">&nbsp;</span> Sim</td>
            <td colspan="2"><span class="quadrado">&nbsp;</span> Não</td>
            <td colspan="2"><span class="quadrado">&nbsp;</span> Não sabe</td>
        </tr>
        <tr class="">
            <td colspan="100%">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="12"><b>HISTÓRICO PESSOAL</b></td>
        </tr>
        <tr class="odd">
            <td colspan="6">Está grávida?</td>
            <td colspan="2"><span class="quadrado">&nbsp;</span> Sim</td>
            <td colspan="2"><span class="quadrado">&nbsp;</span> Não</td>
            <td colspan="2"><span class="quadrado">&nbsp;</span> Não sabe</td>
        </tr>
        <tr class="">
            <td colspan="6">Quantas vezes ficou grávida? _____________________________</td>
            <td colspan="6">Quantos filhos teve? __________________________</td>
        </tr>
        <tr class="odd">
            <td colspan="2">Amamentou?</td>
            <td colspan="2"><span class="quadrado">&nbsp;</span> Sim</td>
            <td colspan="2"><span class="quadrado">&nbsp;</span> Não</td>
            <td colspan="6">Se sim, por quanto tempo? _____________________</td>
        </tr>
        <tr class="">
            <td colspan="12">Qual o motivo deste exame? _____________________________________________________________________________________________</td>
        </tr>
    </table>


</div>

<table border="0" style="width: 100%; margin-top: 50px" cellspacing="1" cellpadding="2">
    <tr>
        <td width="50%" class="align-center">
            ________________________________________<br />
            Assinatura do paciente ou responsável
        </td>
        <td width="50%" class="align-center">
            ________________________________________<br />
            Assinatura do profissional
        </td>
    </tr>
</table>