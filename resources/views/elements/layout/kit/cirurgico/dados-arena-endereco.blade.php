<?php
if (!isset($kit_white) || !$kit_white) {
    $arena = (Object)\App\Arenas::get($arena);
} else {
    $arena = new stdClass();
    $arena->nome = null;
    $arena->endereco = null;
    $arena->numero = null;
    $arena->telefone = null;
}
?>
<div style="margin-top: 20px">
    @if(!empty($arena->nome))
        <div><strong>{!! $arena->nome !!}</strong></div>
        <div>{!! $arena->endereco !!}, {!! $arena->numero !!}</div>
        <div>São Paulo / SP</div>
    @endif

    @if($arena->telefone)
        <div style="margin-top: 0">{!!  \App\Http\Helpers\Mask::telefone($arena->telefone) !!}</div>
    @endif
    <hr style="margin: 10px 0"/>
</div>