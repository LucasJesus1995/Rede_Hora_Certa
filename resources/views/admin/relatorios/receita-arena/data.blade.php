@if(count($res))
    <input type="hidden" id="receita-arena-contrato" value="{!! $contrato !!}" />
    <input type="hidden" id="receita-arena-faturamento" value="{!! $faturamento !!}" />

    <table class="table table-striped table-responsive table-bordered bg-light">
        <tr>
            <th colspan="2">Arenas</th>
            <th width="160px">Receita</th>
        </tr>
        <?php
            $total = [];
        ?>
        @foreach($res AS $row)
            <tr>
                <td width="60px" >
                    <a href="" class="btn-receita-arena" data-arena="{!! $row->id !!}">
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
        <tfoot>
            <tr>
                <td colspan="2"></td>
                <td>
                    <strong>
                        <span class="left">R$</span>
                        <span class="float-right">{!! number_format(array_sum($total), 2, ",", ".") !!}</span>
                    </strong>
                </td>
            </tr>
        </tfoot>
    </table>
@else
    <div class="alert alert-danger"><?php echo e(Lang::get('app.nenhum-registro-encontrado')); ?></div>
@endif