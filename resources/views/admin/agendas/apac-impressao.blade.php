@extends('pdf')

@section('content')
    <title>{!!$title!!}</title>

    <div id="print-kits" class="kit-impressao">
        @include('elements.layout.kit.apac', array('agenda'=>$agenda))
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