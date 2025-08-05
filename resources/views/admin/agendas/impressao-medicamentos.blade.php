@extends('pdf')

@section('content')


    <title>{!!$title!!}</title>

    <div id="print-kits" class="kit-impressao">
        @if(!empty($receitas))
            <?php $i = 0;?>
            @foreach($receitas AS $receita)
                <?php
                echo ($i == 0) ? "<div>" : "<div style='page-break-before:always;'>";
                ?>
                @include('elements.layout.pdf.kit.header', array('agenda'=>$agenda->id, 'box' => false, 'logo_drsaude' => false, 'hidden_preferencial' => false, 'hidden_horarios' => true))
                @include('elements.agenda.receita', array('receita' => $receita, 'agenda' => $agenda))
                <div style="position: absolute; bottom: -50px">
                    @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id])
                </div>
                <?php $i++;?>
            @endforeach
        @endif

        <?php
        echo (empty($receitas)) ? "<div>" : "<div style='page-break-before:always;'>";
        ?>

        @include('elements.layout.pdf.kit.header', array('agenda'=>$agenda->id, 'box' => false, 'logo_drsaude' => false, 'hidden_preferencial' => true, 'hidden_horarios' => true))
        @include('elements.agenda.receita', array('receita' => null, 'agenda' => $agenda))
        <div style="position: absolute; bottom: -50px">
            @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id])
        </div>
    </div>
    </div>
    <script type="text/javascript">
        @if(env('APP_ENV') == 'production')
            try {
            this.print();
        } catch (e) {
            window.onload = window.print;
        }
        @endif
    </script>

@stop