<?php
    $questionario = \App\Http\Helpers\Anamnese::PerguntasByLinhaCuidado($agenda->linha_cuidado);
?>

<div class="bloco">
    @if($agenda->linha_cuidado == 3)
        <h2>FICHA DE ATENDIMENTO MÉDICO EM OTORRINOLARINGOLOGIA</h2>
    @else
        <h2>FICHA DE ATENDIMENTO MÉDICO EM CARDIOLOGIA</h2>
    @endif

        @if($agenda->linha_cuidado == 3)
                <div style="margin: 5px; text-align: center">
                    &nbsp;&nbsp;(&nbsp;&nbsp;&nbsp;) &nbsp;&nbsp; CONSULTA
                    &nbsp;&nbsp;(&nbsp;&nbsp;&nbsp;) &nbsp;&nbsp; RETORNO
                    &nbsp;&nbsp;(&nbsp;&nbsp;&nbsp;) &nbsp;&nbsp; NASOVIDEOLARINGOSCOPIA
                    &nbsp;&nbsp;(&nbsp;&nbsp;&nbsp;) &nbsp;&nbsp; NASO COM M. MULLER

                </div>

        @else
               <div style="margin: 5px; text-align: center">
                   &nbsp;&nbsp;(&nbsp;&nbsp;&nbsp;) &nbsp;&nbsp; CONSULTA
                   &nbsp;&nbsp;(&nbsp;&nbsp;&nbsp;) &nbsp;&nbsp; RETORNO
                   &nbsp;&nbsp;(&nbsp;&nbsp;&nbsp;) &nbsp;&nbsp; ECOCARDIOGRAMA
                   &nbsp;&nbsp;(&nbsp;&nbsp;&nbsp;) &nbsp;&nbsp; ECG
                   &nbsp;&nbsp;(&nbsp;&nbsp;&nbsp;) &nbsp;&nbsp; TESTE ERGOMÉTRICO
               </div>

        @endif


    <div style="margin: 5px 10px; text-align: left">
        Queixa Principal e Duração: <br />
        <hr style="margin-top: 20px" />
    </div>

    <div style="margin: 5px 10px; text-align: left">
        Historia da Moléstia Atual e/ou Evolução: <br />
        <hr style="margin-top: 20px" />
    </div>

    <div style="margin: 5px 10px; text-align: left">
        Exame fisico / Laudo: <br />
        <hr style="margin-top: 20px" />
    </div>

    <div class="bloco" style="margin: 2px 10px">
        <table width="100%" cellspacing="0" cellpadding="0" >
        <?php $ln = 0;?>
        @foreach($questionario AS $p)
            @if($ln == 0)
                    <?php $cor_list = (@$cor_list == "even") ? "odd" : "even";?>
                    <tr class="{!! $cor_list !!}">
            @endif
                <td width="*">{{$p['nome']}}</td>
                <td width="30px" style="text-align: right">{!! $p['cid'] !!} (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            @if($ln == 1)
                </tr>
            @endif

            <?php
                $ln = ($ln == 0) ? 1 : 0;
            ?>
        @endforeach
        </table>
    </div>

    <div style="margin: 5px 10px; text-align: left">
        Conduta: <br />
        <hr style="margin-top: 20px" />
        <hr style="margin-top: 20px" />
    </div>

</div>

<div style="margin-top: -5px">Declaro veracidade das informações e qeu esclareci todas as minhas dúvidas.</div>

<table width="100%" style="margin-top: 60px;">
    <tr>
        <td width="50%">
            <div style="text-align: center !important;">
                <hr style="width: 270px; margin: 0 auto"/>
                Assinatura do paciente ou responsável
            </div>
        </td>
        <td width="50%">
            <div style="text-align: center !important;">
                <hr style="width: 270px; margin: 0 auto"/>
                Assinatura / Carimbo
            </div>
        </td>
    </tr>
</table>