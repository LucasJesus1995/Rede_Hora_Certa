@if(in_array($agenda->linha_cuidado, array('1','2')))
    <div style='page-break-before:always;'>
        @include('elements.layout.pdf.kit.header', array('agenda'=>$agenda->id, 'box' => false, 'exibe_linha' => true, 'logo_drsaude' => false))

        @if($agenda->linha_cuidado == 1)
            @include('elements.agenda.ficha_atendimento.endoscopia', array('agenda' => $agenda))
        @endif
        @if($agenda->linha_cuidado == 2)
            @include('elements.agenda.ficha_atendimento.colonoscopia', array('agenda' => $agenda))
        @endif
        <div style="position: absolute; bottom: -50px">
            @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id])
        </div>
    </div>
@endif
