<div id="relatorio-receita-detalhe-linha-cuidado">
    <div>
        <ol class="breadcrumb">
            <li class="active">{!! $arena->nome !!}</li>
            <span class="float-right">
                R$ {!! number_format(array_sum(array_column($res->toArray(), 'receita')) , 2, ",", ".") !!}
            </span>
        </ol>
    </div>

    <input type="hidden" id="receita-arena-contrato" value="{!! $contrato !!}" />
    <input type="hidden" id="receita-arena-faturamento" value="{!! $faturamento !!}" />
    <input type="hidden" id="receita-arena" value="{!! $arena->id !!}" />

    <table class="table table-striped table-responsive table-bordered bg-light">
        <tr>
            <th colspan="2">Especialidade</th>
            <th width="160px">Receita</th>
        </tr>
        <?php
            $total = [];
        ?>
        @foreach($res AS $row)
            <tr>
                <td width="60px" >
                    <a href="" class="btn-receita-arena-linha_cuidado" data-linha-cuidado="{!! $row->id !!}">
                        <i class="fa fa-search"></i>
                    </a>
                </td>
                <td>{!! $row->nome !!}</td>
                <td>
                    <span class="left">R$</span>
                    <span class="float-right">{!! number_format($row->receita, 2, ",", ".") !!}</span>
                </td>
            </tr>
            <?php
                $total[] = $row->receita;
            ?>
        @endforeach
    </table>
</div>