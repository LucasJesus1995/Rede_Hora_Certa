@if(!empty($grid))
    @foreach($grid AS $item)
        <small>{!! $item->codigo !!} - {!! $item->nome !!}</small><br />
    @endforeach
@endif