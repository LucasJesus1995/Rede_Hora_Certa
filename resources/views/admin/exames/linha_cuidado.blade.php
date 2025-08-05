<?php
if(!empty($linha_cuidado_exames)):
?>
<table class="table table-striped table-responsive table-bordered  bg-light ">
    <thead>
    <tr role="row">
        <th class="w-64">#</th>
        <th>Especialidade</th>
    </tr>
    </thead>
    <tbody>
    @foreach($linha_cuidado_exames AS $row)
        <?php
        $checked =  !empty($row->exames_linha_cuidado_id) ? "checked" : null;
        ?>
        <tr class="">
            <td>
                <input type="checkbox" name="default_{{$row->id}}" id="default_{{$row->id}}" class="btn-checked-exame-linha-cuidado" data-exame="{{$exame}}"
                       data-linha-cuidado="{{$row->id}}" {{$checked}} />
            </td>
            <td>
                <label for="default_{{$row->id}}">
                    {{$row->nome}}
                </label>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<?php
else:
    echo "<div class='panel bg-danger pos-rlt'>
                <span class='arrow top  b-danger '></span>
                <div class='panel-body'>" . Lang::get('grid.nenhum-registro-encontrado') . "</div>
              </div>";
endif;
?>