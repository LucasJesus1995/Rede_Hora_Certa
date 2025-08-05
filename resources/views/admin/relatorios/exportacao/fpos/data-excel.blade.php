@if(!empty($procedimentos))
    <html>
    <link media="all" type="text/css" rel="stylesheet" href="src/css/relatorio/diretoria.css">

    @if(!empty($procedimentos))
        <table width="100%" border="1">
            <tr>
                <th width="15">Código</th>
                <th width="17">Modalidade</th>
                <th width="100">Procedimento</th>
                <th width="17">Complexidade</th>
                <th width="17">Valor unitário</th>
                <th width="15">Quantidade</th>
                <th width="20">Valor mensal</th>
            </tr>
            <?php
            $ln = 2;
            ?>
            @foreach($procedimentos AS $row)
                <tr class="line {!! $zebra =  (@$zebra == "even") ? "odd" : "even" !!}">
                    <td class="center">{!! \App\Http\Helpers\Mask::CodigoProcedimento($row['sus']) !!}</td>
                    <td class="center">{!! \App\ProcedimentoModalidade::getSigla($row['modalidade']) !!}</td>
                    <td>{!! $row['nome'] !!}</td>
                    <td class="center">{!! \App\ProcedimentoComplexidade::getSigla($row['complexidade']) !!}</td>
                    <td>@if(!empty($row['valor_unitario'])) {!! $row['valor_unitario'] !!}  @endif</td>
                    <td>@if(!empty($row['quantidade'])) {!! $row['quantidade'] !!}  @endif</td>
                    <td>=(E{!! $ln !!}*F{!! $ln !!})</td>
                </tr>
                <?php $ln++; ?>
            @endforeach
        </table>
    @endif

    </html>
@endif