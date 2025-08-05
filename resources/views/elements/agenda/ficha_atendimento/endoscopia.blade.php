<?php
    $linha_cuidado = \App\LinhaCuidado::get($agenda->linha_cuidado);
    $medicamentos = \App\Medicamentos::ByLinhaCuidado($agenda->linha_cuidado);
?>
<div id="impressao" class="impressao-small" style="">

    @include('elements.agenda.ficha_atendimento.auxiliar.procedimento')
    @include('elements.agenda.ficha_atendimento.auxiliar.laudo')

    <div class="bloco">
        <h2 style="text-align: left">LAUDO ENDOSCÓPICO PRINCIPAL: </h2>
        <div class="content">
            @include('elements.agenda.ficha_atendimento.auxiliar.laudo_itens')
        </div>
    </div>

    @include('elements.agenda.ficha_atendimento.auxiliar.teste_urease')

    <div class="bloco">
        <h2 style="text-align: left">PRESCRIÇÃO MÉDICA: </h2>
        <div class="content">
            <table width="100%" cellspacing="0" cellpadding="2" class="table-border" style="margin: 5px">
                <thead>
                    <tr>
                        <th>DESCRIÇÃO</th>
                        <th width="10%">QTDE</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1;?>
                    @foreach($medicamentos AS $label)
                        <tr class="">
                            <td>{{str_pad($i, 2, "0", STR_PAD_LEFT)}}. (&nbsp;&nbsp;&nbsp;&nbsp;) {{$label}}</td>
                            <td></td>
                        </tr>
                        <?php $i++; ?>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @include('elements.agenda.ficha_atendimento.auxiliar.evolucao')
    @include('elements.agenda.ficha_atendimento.auxiliar.assinatura')

</div>