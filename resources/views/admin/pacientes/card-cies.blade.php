@extends('pdf-card-cies')

@section('content')
    @if(!empty($paciente))
        <?php
        $bar_code = new \Picqer\Barcode\BarcodeGeneratorPNG();
        ?>
        <div class="size-card  position-relative">
            <span class="card-descricao card-descricao-nome">
                {!! \App\Http\Helpers\Util::NomeSobrenome(($paciente->nome_social) ? $paciente->nome_social : $paciente->nome) !!}
            </span>

            <span class="card-descricao card-descricao-cpf">
                {!! str_replace(" "," &nbsp;", $cartao) !!}
            </span>

            <span class="card-codigo-barras">
                <img src="data:image/png;base64,' {!!  base64_encode($bar_code->getBarcode($paciente->cpf, $bar_code::TYPE_CODE_128, 2.2, 60))  !!} '"
                     style="margin: 2px"/>
            </span>
        </div>
    @else
        <div>Paciente não encontrado!</div>
    @endif
@stop