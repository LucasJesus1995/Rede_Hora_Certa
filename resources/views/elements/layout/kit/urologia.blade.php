<br/>

<table width="100%">
    <tr>
        <td width="50%">
            <div class="bloco" style="margin: 0">
                <h2>Origem</h2>
                <div style="margin: 22px 5px; line-height: 22px">
                    <hr/>
                    <br/>
                    <hr/>
                </div>
            </div>
        </td>
    </tr>
</table>
<table width="100%">
    <tr>
        <td width="50%">
            <div class="bloco" style="margin: 0">
                <h2>Queixa Principal</h2>
                <div style="margin: 22px 5px; line-height: 22px">
                    <hr/>
                    <br/>
                    <hr/>
                </div>
            </div>
        </td>
    </tr>
</table>

<table width="100%" cellspacing="0" cellpadding="0">
    <tr>
        <td width="30%">
            <div class="bloco" style=" ">
                <table width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                        <th class="title" colspan="2">Antecedentes Pessoais</th>
                    </tr>
                    <?php $ln = 0;?>
                    @foreach(\App\AnamnesePerguntas::FormularioUrologia(1) AS $descricao)
                        @if($ln == 0)
                            <?php $cor_list = (@$cor_list == "even") ? "odd" : "even";?>
                            <tr class="{!! $cor_list !!}">
                                @endif
                                <td width="50%" style="text-align: left">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)&nbsp;{!! $descricao !!} </td>
                                @if($ln == 1)
                            </tr>
                        @endif

                        <?php
                        $ln = ($ln == 0) ? 1 : 0;
                        ?>
                    @endforeach
                </table>
            </div>
        </td>
        <td width="70%">
            <div class="bloco" style="margin: 4px 3px ">
                <table width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                        <th class="title" colspan="2">Medicamentos em uso</th>
                    </tr>
                    <?php $ln = 0;?>
                    @foreach(\App\AnamnesePerguntas::FormularioUrologia(2) AS $descricao)
                        @if($ln == 0)
                            <?php $cor_list = (@$cor_list == "even") ? "odd" : "even";?>
                            <tr class="{!! $cor_list !!}">
                                @endif
                                <td width="50%" style="text-align: left">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)&nbsp;{!! $descricao !!} </td>
                                @if($ln == 1)
                            </tr>
                        @endif

                        <?php
                        $ln = ($ln == 0) ? 1 : 0;
                        ?>
                    @endforeach
                </table>
            </div>
        </td>
    </tr>

</table>

<div class="bloco" style="margin: 6px 3px ">
    <h2>Exame físico</h2>
    <div style="margin: 22px 5px; line-height: 22px">
        <hr/>
        <br/>
        <hr/>
        <br/>
        <hr/>
        <br/>
        <hr/>
        <br/>
        <hr/>
    </div>
</div>

<div class="bloco" style="margin: 6px 3px ">
    <h2>Hipótese diagnóstica</h2>
    <div style="margin: 22px 5px; line-height: 22px">
        <hr/>
        <br/>
        <hr/>
        <br/>
        <hr/>
        <br/>
        <hr/>
        <br/>
        <hr/>
        CID:
    </div>
</div>


<div class="align-center" style=" line-height: 16px; margin-top: 150px">
    ____________________________________________<br>
    Assinatura médica
</div>

