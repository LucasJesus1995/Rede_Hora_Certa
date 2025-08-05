@extends('pdf')

@section('content')
    <title>{!!$title!!}</title>

    <div id="print-kits" class="kit-impressao">
        @if(!empty($messagem_error))
            <h4 class="center">{!! $messagem_error !!}</h4>
        @else
            @if($kit == 1)
                @include('elements.layout.kit.cirurgico.informativos.escleroterapia', ['agenda'=>$agenda])
            @endif

            @if($kit == 2)
                @include('elements.layout.kit.cirurgico.termo-consentimento-urologia', ['agenda'=>$agenda])
            @endif

            @if($kit == 3)
                @include('elements.layout.kit.oftamologia-mapeamento-retina', array('agenda'=>$agenda))
            @endif

            @if($kit == 4)
                @include('elements.layout.kit.cirurgico.termo-consentimento-yag-laser', array('agenda'=>$agenda))
            @endif

            @if($kit == 5)
                @include('elements.layout.kit.cirurgico.termo-consentimento-responsabilidade', array('agenda'=>$agenda))
            @endif

        @endif
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