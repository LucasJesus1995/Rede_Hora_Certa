<?php
$medicamento = null;
if (!empty($receita['medicamento'])) {
    $medicamento = (Object)\App\Medicamentos::get($receita['medicamento']);
}
$linha_cuidado = (Object)\App\LinhaCuidado::get($agenda->linha_cuidado);
?>


<h1 style="margin: 10px 0; text-align: center">RECEITUÁRIO DE CONTROLE ESPECIAL</h1>
<div style="margin: 10px 0; text-align: left; font-size: 20px">PROCEDIMENTO <strong style="font-size: 20px">{{$linha_cuidado->nome}}</strong></div>

<div class="bloco">
    <h2 style="text-align: left">DESCRIÇÃO DO MEDICAMENTO E POSOLOGIA</h2>
    <div class="content">
        <table width="100%" cellspacing="0" cellpadding="3">
            <tbody>
            <tr>
                <td colspan="3" style="line-height: 20px">
                    @if(!empty($medicamento))
                        <b>{!! $medicamento->nome !!}</b>
                        @if(!empty($medicamento->descricao))
                            <div style="padding: 4px; margin-top: -2px; font-style: italic"> {!! $medicamento->descricao !!}</div>
                        @endif
                    @endif
                </td>
                <td nowrap style="text-align: right;line-height: 20px;">
                    @if(!empty($medicamento))
                    @if(!empty($medicamento->quantidade_padrao))
                        <b>{!! $medicamento->quantidade_padrao !!}</b>
                    @else
                        ____________________
                    @endif
                    @endif
                    &nbsp;&nbsp;&nbsp;&nbsp;
                </td>
            </tr>
            <tr>
                <td width="25%" height="300px">&nbsp;</td>
                <td width="25%">&nbsp;</td>
                <td width="25%">&nbsp;</td>
                <td width="25%">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center;">______________________________________________________<br/>Médico(a) e carimbo</td>
                <td colspan="2" style="text-align: center">Data: ____/_____/____________</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="bloco">
    <h2 style="text-align: center">DISTRIBUIÇÃO / DISPENSAÇÃO</h2>
    <div class="content">
        <div style="text-align: left; margin: 0; padding: 10px;">
            <b>FARMÁCIA CENTRAL – ASSOCIAÇÃO BENEFICENTE EBENEZER</b><br/>
            CIES GLOBAL - CNPJ: 06.950.310/0001-53<br/>
            Rua Lutécia, 169, Vila Carrão - São Paulo/SP<br/>
            CEP: 03423-000<br/>
            Tel.: (11) 2091 5506
        </div>

        <div style="text-align: left; margin: 0; padding: 10px; margin-bottom: 100px">
            <b>Farmacêutica responsável técnica:</b> MARLA CRUZ DE ARAÚJO<br/>
            <b>CRF/SP: </b>96.240
        </div>

        <div style="text-align: left; margin: 0; padding: 10px;">
            <hr/>
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="42px" style="text-align: left">
                        <img src="src/image/logo/ebenezer.png" class="img-responsive" style="height: 50px; width: 40px"/>
                    </td>
                    <td style="text-align: left">
                        <strong>ASSOCIAÇÃO BENEFICENTE EBENEZER</strong>
                        <div>Rua Salvador Simões, 801 - 10ª andar | Vila Dom Pedro I | São Paulo/SP</div>
                        <div>CEP 04276-000 | Tel.: +55 (11) 5571 8017</div>
                        <div>CNPJ: 06.950.310/0001-53</div>
                    </td>
                </tr>
            </table>
        </div>

    </div>
</div>