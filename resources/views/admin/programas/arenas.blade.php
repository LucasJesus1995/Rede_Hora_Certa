<?php
if(!empty($arenas)):
?>
<div class="row">
    <div class="col-md-12">
        <input class='form-control' placeholder='Pesquisa de Arena' id="input_busca_programas_arenas" type="text" />
    </div>
</div>
<br>
<table id="table_programas_arenas" class="table table-striped table-responsive table-bordered  bg-light ">
    <thead>
    <tr role="row">
        <th class="w-64">#</th>
        <th class="">nome</th>
    </tr>
    </thead>
    <tbody>
    @foreach($arenas AS $row)
        <?php
        $checked = array_key_exists($row['id'], $arenas_programa) ? 'checked' : null;
        ?>
        <tr>
            <td>
                <input type="checkbox" {{ $checked }} class="btn-checked-programa-arenas"
                       id-arenas="{{$row['id']}}" id-programa="{{$programa}}"/>
            </td>
            <td class='row_td_search'>{{$row['nome']}}</td>
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