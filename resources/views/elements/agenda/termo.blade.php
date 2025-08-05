@if(in_array($agenda->linha_cuidado, array('1','2','6')))
    <div style='page-break-before:always;'>
        @include('elements.layout.pdf.kit.header', array('agenda'=>$agenda->id, 'box' => false, 'exibe_linha' => true, 'logo_drsaude' => false))
        <br/>
        <br/>
        <?php
        switch($agenda->linha_cuidado){
        case 1 :
        ?>
        @include('elements.agenda.endoscopia', array('agenda' => $agenda))
        <?php
        break;
        case 2 :
        ?>@include('elements.agenda.colonoscopia', array('agenda' => $agenda))<?php
        break;
        case 6 :
        ?>@include('elements.agenda.eletroneuromiografia', array('agenda' => $agenda))<?php
        break;
        }
        ?>
        <div style="position: absolute; bottom: -50px">
            @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id])
        </div>
    </div>
@endif