<?php
if(!empty($grid->items())):
?>

<table class="table table-striped table-responsive table-bordered bg-light ">
    <thead>
    <tr role="row">
        <th class="w-64">#</th>
        <th>SUS</th>
        <th>Nome</th>
        <th>Multiplicador</th>
        <th>Valor</th>
        <th class="w-64">{!!Lang::get('grid.acao')!!}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($grid AS $row)
        <tr class="grid-status-{{$row->ativo}}">
            <td>{{$row->id}}</td>
            <td nowrap>{{ \App\Http\Helpers\Mask::CodigoProcedimento($row->sus) }}</td>
            <td>{{$row->nome}}</td>
            <td>{{$row->multiplicador_medico}}</td>
            <td>{{$row->valor_medico}}</td>
            <td nowrap>

                <a href="/admin/procedimentos-medicos/entry/{{$row->id}}"
                   class="btn btn-rounded btn-xs btn-info waves-effect"><i class="fa fa-edit"></i></a>

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