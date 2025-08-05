@if(count($res))
    <div>
        <ol class="breadcrumb">
            <li class="active">{!! \App\Http\Helpers\Util::getMesNome($periodo[1]) !!}</li>
            <span class="float-right">
                {!! array_sum(array_column($res->toArray(), 'total'))!!}
            </span>
        </ol>
    </div>

    <table class="table table-striped table-responsive table-bordered bg-light">

        <tr>
            <th colspan="2">Procedimento</th>
            <th width="100">Total</th>
        </tr>
        @foreach($res AS $row)
            <tr>
                <td width="60px" >
                    <a href="" class="btn-relatorio-gordura-detalhado-procedimento" data-contrato="{!! $contrato !!}" data-periodo="{!! implode("-", $periodo) !!}" data-procedimento="{!! $row->id !!}" >
                        <i class="fa fa-search"></i>
                    </a>
                </td>
                <td>{!! $row->nome !!}</td>
                <td class="align-right">{!! $row->total !!}</td>
            </tr>
        @endforeach
    </table>
@else
    <div class="alert alert-danger"><?php echo e(Lang::get('app.nenhum-registro-encontrado')); ?></div>
@endif