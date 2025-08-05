<?php
if(!empty($grid->items())):
?>
<table class="table table-striped table-responsive table-bordered  bg-light ">
    <thead>
    <tr role="row">
        <th class="w-64">#</th>
        <th>{!!Lang::get('grid.nome')!!}</th>
        <th>{!!Lang::get('app.cns')!!}</th>
        <th>{!!Lang::get('app.sexo')!!}</th>
        <th>Contato</th>
        <th class="w-64">{!!Lang::get('grid.acao')!!}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($grid AS $row)
        <tr class="grid-status-{{$row->ativo}}">
            <td>{{$row->id}}</td>
            <td>
                {!! $row->getNomeSocialLayout() !!}<br/>
                @if(!empty($row->cpf))
                    {{ \App\Http\Helpers\Mask::Cpf($row->cpf)}}<br/>
                @endif
                @if(!empty($row->nascimento))
                    {{ \App\Http\Helpers\Util::DB2Users($row->nascimento)}}
                @endif
            </td>
            <td>{{$row->cns}}</td>
            <td><?php if (!empty($row->sexo)) echo \App\Http\Helpers\Util::Sexo($row->sexo) ?></td>
            <td>
                @if(!empty($row->celular)) {!! \App\Http\Helpers\Mask::telefone($row->celular) !!}<br/> @endif
                @if(!empty($row->telefone_residencial)) {!! \App\Http\Helpers\Mask::telefone($row->telefone_residencial) !!}<br/> @endif
                @if(!empty($row->telefone_contato)) {!! \App\Http\Helpers\Mask::telefone($row->telefone_contato) !!}<br/> @endif
                @if(!empty($row->telefone_comercial)) {!! \App\Http\Helpers\Mask::telefone($row->telefone_comercial) !!}<br/> @endif
            </td>
            <td nowrap>
                <a href="" data-id="{{$row->id}}" class="btn btn-rounded btn-xs btn-success waves-effect btn-paciente-prontuario"><i class="mdi-action-assignment"></i></a>
                @if(!empty($row->cpf))
                    <a href="" data-cpf="{{$row->cpf}}" class="btn btn-rounded btn-xs btn-warning waves-effect btn-paciente-card-cies"><i class="mdi-action-picture-in-picture"></i></a>
                @endif
                <a href="/admin/pacientes/entry/{{$row->id}}" class="btn btn-rounded btn-xs btn-info waves-effect"><i class="fa fa-edit"></i></a>
                <a href="/admin/pacientes/delete/{{$row->id}}" class="btn-grid-delete btn btn-rounded btn-xs btn-danger waves-effect"><i class="fa fa-remove"></i></a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

{!! $grid->render() !!}
<input type="hidden" id="url-pagination" value="{{urldecode($_SERVER['REQUEST_URI'])}}"/>
<?php
else:
    echo "<div class='panel bg-danger pos-rlt'>
                <span class='arrow top  b-danger '></span>
                <div class='panel-body'>" . Lang::get('grid.nenhum-registro-encontrado') . "</div>
              </div>";
endif;
?>