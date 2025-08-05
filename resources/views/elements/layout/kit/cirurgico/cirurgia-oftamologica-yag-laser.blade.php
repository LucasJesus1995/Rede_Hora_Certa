@include('elements.layout.pdf.kit.header', array('agenda'=>$agenda->id, 'box' => true, 'logo_drsaude' => false, 'hidden_logo_header' => true))
@include('elements.layout.kit.questionario-especifico', array())
@include('elements.layout.kit.acolhimento', array())
@include('elements.layout.kit.prescricao-medica', array())
<div style="position: absolute; bottom: -50px">
    @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id])
</div>
<div style='page-break-before:always;'>
    <div>
        @include('elements.layout.pdf.kit.header', array('agenda'=>$agenda->id, 'box' => false, 'exibe_linha' => false, 'logo_drsaude' => true))
        @include('elements.layout.kit.yag-laser',  array('agenda'=>$agenda))
        <div style="position: absolute; bottom: -50px">
            @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id])
        </div>
    </div>
</div>