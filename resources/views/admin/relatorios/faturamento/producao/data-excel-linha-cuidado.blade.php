@if(!empty($linha_cuidado))
    <html>
    <link media="all" type="text/css" rel="stylesheet" href="src/css/relatorio/diretoria.css">

    <?php
    $ln = 2;
    $ln_inicial = $ln;
    ?>
    @if(!empty($linha_cuidado))
        <table class="table table-striped table-responsive table-bordered  bg-light">
            <tr>
                <th width="80">Especialidade</th>
                <th width="15">Agendado</th>
                <th width="15">Produção</th>
                <th width="15">Faturamento</th>
                <th width="18">Faturado ($)</th>
            </tr>
            @foreach($linha_cuidado AS $k => $nome)
                <tr class="line {!! $zebra =  (@$zebra == "even") ? "odd" : "even" !!}">
                    <td class="align-left">{!! $nome !!}</td>
                    <td class="align-left">=SUMIF(PROCEDIMENTOS!A:A,A{!! $ln !!},PROCEDIMENTOS!E:E)</td>
                    <td class="align-left">=SUMIF(PROCEDIMENTOS!A:A,A{!! $ln !!},PROCEDIMENTOS!F:F)</td>
                    <td class="align-left">=SUMIF(PROCEDIMENTOS!A:A,A{!! $ln !!},PROCEDIMENTOS!G:G)</td>
                    <td class="align-left">=SUMIF(PROCEDIMENTOS!A:A,A{!! $ln !!},PROCEDIMENTOS!H:H)</td>
                </tr>
                <?php $ln++; ?>
            @endforeach
            <tr class="line {!! $zebra =  (@$zebra == "even") ? "odd" : "even" !!}">
                <th class="align-left"></th>
                <th class="align-left">=SUM(B{!! $ln_inicial !!}:B{!! ($ln-1) !!})</th>
                <th class="align-left">=SUM(C{!! $ln_inicial !!}:C{!! ($ln-1) !!})</th>
                <th class="align-left">=SUM(D{!! $ln_inicial !!}:D{!! ($ln-1) !!})</th>
                <th class="align-left">=SUM(E{!! $ln_inicial !!}:E{!! ($ln-1) !!})</th>
            </tr>
        </table>
    @endif
    </html>
@endif