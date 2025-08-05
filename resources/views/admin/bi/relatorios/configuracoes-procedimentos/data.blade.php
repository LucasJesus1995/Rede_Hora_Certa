<html>
<link media="all" type="text/css" rel="stylesheet" href="src/css/relatorio/diretoria.css">

<table width="100%" border="1">

    @foreach($linhas_cuidado AS $cod_linha_cuidado => $linha_cuidado)
        <?php
        $procedimentos = \App\Procedimentos::getByLinhaCuidado($cod_linha_cuidado);
        ?>

        @if(!empty($procedimentos[0]))
            <tr>
                <th width="30" colspan="13">{!! $linha_cuidado !!}</th>
            </tr>
            <tr>
                <th width="8">ID</th>
                <th width="90">Procedimentos (Descrição)</th>
                <th width="18">Código</th>
                <th width="12">Obrigatório</th>
                <th width="12">Quantidade</th>
                <th width="14">Multiplicador</th>
                <th width="16">Faturamento</th>
                <th width="10">Maximo</th>
                <th width="12">CBO</th>
                <th width="10">Contador</th>
                <th width="10">Ordem</th>
                <th width="12">Servico BPA</th>
                <th width="12">Class BPA</th>
            </tr>
            @foreach($procedimentos AS $procedimento)
                <tr class="line {!! $zebra =  (@$zebra == "even") ? "odd" : "even" !!}">
                    <td class="center">{!! $procedimento->id !!}</td>
                    <td>{!! $procedimento->nome !!}</td>
                    <td>{!! \App\Http\Helpers\Mask::CodigoProcedimento($procedimento->sus) !!}</td>
                    <td class="center">{!! \App\Http\Helpers\Util::Ativo($procedimento->obrigatorio) !!}</td>
                    <td>{!! $procedimento->quantidade !!}</td>
                    <td>{!! $procedimento->multiplicador !!}</td>
                    <td>@if($procedimento->forma_faturamento) {!! \App\Http\Helpers\Util::FormaFaturamento($procedimento->forma_faturamento) !!} @endif</td>
                    <td>{!! $procedimento->maximo !!}</td>
                    <td class="center">{!! $procedimento->cbo !!}</td>
                    <td>{!!  \App\Http\Helpers\Util::Ativo($procedimento->contador) !!}</td>
                    <td>{!! $procedimento->ordem !!}</td>
                    <td>{!! $procedimento->servico_bpa !!}</td>
                    <td>{!! $procedimento->class_bpa !!}</td>
                </tr>
            @endforeach
            <tr></tr>
        @endif

    @endforeach

</table>
</html>