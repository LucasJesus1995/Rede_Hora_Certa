<?php
$profissional = null;
$cbos = null;

if (!empty($agenda->medico)) {
    $profissional = \App\Profissionais::getMedicoByID($agenda->medico);

    $cbos = \App\ProfissionaisCbo::getCboByProfissional($profissional->id);
}

$ids = \App\Http\Helpers\Cirurgico\KitImpressaoHelpers::getProcedimentos($agenda->linha_cuidado, $sub_especialidade);
if (!empty($ids)) {
    $procedimentos = \App\Procedimentos::whereIn('id', $ids)->orderBy('nome', 'asc')->get();
}
?>
<div style='page-break-before:always;' class="kit-impressao">
    @include('elements.layout.pdf.kit.header', array('agenda'=>$agenda->id, 'box' => false, 'logo_drsaude' => false, 'hidden_logo_header' => true))

    <h1 class="title">AVALIAÇÃO DE PÓS PROCEDIMENTO IMEDIATO</h1>

    <div class="bloco" style="margin: 0; margin-top: 10px;">
        <h2 style="text-align: left">Queixas</h2>
        <div style="margin: 22px 5px; line-height: 22px">
            <hr/>
            @for($i = 0; $i < 8; $i++)
                <br/>
                <hr/>
            @endfor
        </div>
    </div>

    <div class="bloco" style="margin: 0; margin-top: 10px;">
        <h2 style="text-align: left">Exame Físico:</h2>
        <div style="margin: 10px 5px; line-height: 17px; height: 150px; font-style: italic ">

        </div>
    </div>

    <div class="bloco" style="margin: 0; margin-top: 10px;">
        <h2 style="text-align: left">CONDUTA</h2>
        <div style="margin: 10px 5px; line-height: 17px; height: 150px; font-style: italic ">

        </div>
    </div>

    <p style="margin-top: 70px">@include('elements.layout.kit.cirurgico.assinatura-carimbo-medico')</p>


    <div style="position: absolute; bottom: -50px">
        @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id])
    </div>
</div>