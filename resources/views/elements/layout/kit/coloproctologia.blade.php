<br/>
<div class="bloco" style="width: 48.5%; margin-left: 50.5%">
    <table border="0" style="width: 100%;" cellspacing="1" cellpadding="2">
        <tr>
            <td>
                <table border="0" style="width: 100%;" cellspacing="1" cellpadding="10">
                    <tr>
                        <td width="20%">&nbsp;</td>
                        <td width="33%">
                            ( &nbsp;&nbsp;&nbsp; ) <strong>Consulta</strong>
                        </td>
                        <td width="33%">
                            ( &nbsp;&nbsp;&nbsp; ) <strong>Retorno</strong>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

    </table>
</div>

<table width="100%">
    <tr>
        <td width="50%">
            <div class="bloco" style="margin: 0">
                <h2>Queixa Principal</h2>
                <div style="margin: 22px 5px; line-height: 22px">
                    <hr/><br />
                    <hr/>
                </div>
            </div>
        </td>
        <td width="50%">
            <div class="bloco" style="margin: 0">
                <h2>História da Moléstia (Atual)</h2>
                <div style="margin: 22px 5px; line-height: 22px">
                    <hr/><br />
                    <hr/>
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <td width="50%">
            <div class="bloco" style="margin: 0">
                <h2>Antecedentes pessoais</h2>
                <div style="margin: 22px 5px; line-height: 22px">
                    <hr/><br />
                    <hr/>
                </div>
            </div>
        </td>
        <td width="50%">
            <div class="bloco" style="margin: 0">
                <h2>Antecedentes familiares</h2>
                <div style="margin: 22px 5px; line-height: 22px">
                    <hr/><br />
                    <hr/>
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <div class="bloco" style="margin: 0">
                <h2>Exame físico</h2>
                <div style="margin: 22px 5px; line-height: 22px">
                    <hr/><br />
                    <hr/>
                </div>
            </div>
        </td>
    </tr>
</table>

<div class="bloco" style="margin: 4px 3px ">
    <table width="100%" cellspacing="0" cellpadding="0" >
        <tr>
            <th class="title">CID</th>
            <th class="title">HIPÓTESE DIAGNÓSTICA</th>
            <th class="title">&nbsp;</th>
            <th class="title">&nbsp;</th>
        </tr>
        <?php $ln = 0;?>
        @foreach(\App\AnamnesePerguntas::FormularioColoproctologia(1) AS $cid => $descricao)
            @if($ln == 0)
                <?php $cor_list = (@$cor_list == "even") ? "odd" : "even";?>
                <tr class="{!! $cor_list !!}">
                    @endif
                    <td width="10%" style="text-align: left">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)&nbsp;{!! $cid !!} </td>
                    <td width="40%">{!! $descricao !!}</td>
                    @if($ln == 1)
                </tr>
            @endif

            <?php
            $ln = ($ln == 0) ? 1 : 0;
            ?>
        @endforeach
    </table>
</div>

<div class="bloco" style="margin: 6px 3px ">
    <h2>Conduta</h2>
    <div style="margin: 22px 5px; line-height: 22px">
        <hr/><br />
        <hr/><br />
        <hr/><br />
        <hr/><br />
        <hr/>
        ORGÃO:
    </div>
</div>



<div class="align-center" style=" line-height: 16px; margin-top: 50px">
    ____________________________________________<br>
    Assinatura médica
</div>

