@if(count($res))
    <input type="hidden" id="receita-arena-contrato" value="{!! $contrato !!}" />

    <table class="table table-striped table-responsive table-bordered bg-light">

        <tr>
            <th colspan="2">Mês</th>
            <th width="100">Total</th>
        </tr>
        <?php
            $total = [];
        ?>
        @foreach($res AS $ano => $mes)
            <?php

            ?>
            <tr>
                <th colspan="100%" width="100" style="background-color: #C9C9C9 !important;">
                    {!! $ano !!}
                    <span class="float-right">
                        {!! array_sum(array_column($mes, 'total')) !!}
                    </span>
                </th>
            </tr>
            <tbody>
            @foreach($mes AS $k => $row)
                <tr>
                    <td width="60px" >
                        <a href="" class="btn-relatorio-gordura-detalhado" data-periodo="{!! $row['data'] !!}" >
                            <i class="fa fa-search"></i>
                        </a>
                    </td>
                    <td>{!! \App\Http\Helpers\Util::getMesNome($k) !!}</td>
                    <td class="align-right">{!! $row['total'] !!}</td>
                </tr>
            @endforeach
            </tbody>
        @endforeach
    </table>
@else
    <div class="alert alert-danger"><?php echo e(Lang::get('app.nenhum-registro-encontrado')); ?></div>
@endif