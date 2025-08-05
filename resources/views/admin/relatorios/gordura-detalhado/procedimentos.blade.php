@if(count($res))
    <div>
        <ol class="breadcrumb">
            <li class="">
                <a href="" class="btn-relatorio-gordura-detalhado" data-periodo="{!! $ano_mes !!}" >
                    {!! \App\Http\Helpers\Util::getMesNome($periodo[1]) !!}
                </a>
            </li>
            <li class="active">{!! $procedimento->nome !!}</li>
            <span class="float-right">
                {!! array_sum(array_column($res->toArray(), 'total'))!!}
            </span>
        </ol>
    </div>

    <table class="table table-striped table-responsive table-bordered bg-light">

        <tr>
            <th>Agenda</th>
            <th>Paciente</th>
            <th>Arena</th>
            <th>Status</th>
            <th width="40">Total</th>
        </tr>
        @foreach($res AS $row)
            <tr>
                <td>
                    <span class="badge badge-sm bg-info" style="padding: 2px 9px">{{\App\Http\Helpers\Util::DBTimestamp2UserTime2($row->data)}}</span>
                    <br /><small>{{$row->id}}</small>
                    <br /><small>{{\App\Http\Helpers\Util::DBTimestamp2UserDate($row->data)}}</small>
                </td>
                <td>
                    <strong>{{ $row->nome}}</strong>
                    @if(!empty($row->cns))
                        <span class='text-muted block text-xs'><strong>CNS:</strong> {{ \App\Http\Helpers\Mask::SUS($row->cns) }}</span>
                    @endif

                    @if($row->cpf)
                        <span class='text-muted block text-xs'><strong>CPF:</strong> {{ \App\Http\Helpers\Mask::Cpf($row->cpf) }}</span>
                    @endif
                </td>
                <td>
                    <strong>{{$row->arena_nome}}</strong>
                    <br />{{$row->linha_cuidado_nome}}
                </td>
                <td>
                    <strong class="box-agenda-status">
                        {!! \App\Http\Helpers\Util::StatusAgenda($row->status) !!}
                    </strong>
                </td>
                <td class="align-right">{!! $row->total !!}</td>
            </tr>
        @endforeach
    </table>
@else
    <div class="alert alert-danger"><?php echo e(Lang::get('app.nenhum-registro-encontrado')); ?></div>
@endif