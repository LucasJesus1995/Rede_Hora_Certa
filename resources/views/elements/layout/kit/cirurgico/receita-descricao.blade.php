<?php
if(!isset($kit_white) || !$kit_white){

}else{
    $agenda = new stdClass();
    $agenda->arena = null;
    $agenda->id = null;
    $agenda->linha_cuidado = $linha_cuidado->id;
}
?>

<div style="margin: 30px; position: relative; width: 490px !important; height: 730px !important; " class="">
    @include('elements.layout.pdf.kit.header', array('agenda'=>$agenda->id, 'box' => false, 'logo_drsaude' => false, 'hidden_logo_header' => true))

    <h1 class="title">RECEITA</h1>

    <div style="" class="line-height-16">
        @foreach(\App\Http\Helpers\Cirurgico\KitImpressaoHelpers::getReceita($agenda->linha_cuidado, $sub_especialidade) AS $k => $rows)
            @if(is_array($rows))
                <div>
                    <strong>{!! $k !!}</strong>
                </div>
                @foreach($rows AS $row)
                    <div style="margin-left: 10px">{!! $row !!}</div>
                @endforeach
                <br/>
            @else
                <div style="">{!! $rows !!}</div>
            @endif

        @endforeach
    </div>

    <div style="position: absolute; bottom: -150px; left: 0">

        <div>
            @include('elements.layout.kit.cirurgico.dados-arena-endereco', ['arena' => $agenda->arena])
        </div>

        @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id])
    </div>

</div>