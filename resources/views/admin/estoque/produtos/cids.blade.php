<?php
    if(!empty($cids)):
        ?>
        
        <div class="row">
            <div class="col-md-12">
                <input class='form-control' placeholder='Pesquisa de CID' id="input_busca_guia_cids" type="text"></input>
            </div>
        </div>
        <br>
        <table id="table_guia_cids" class="table table-striped table-responsive table-bordered  bg-light " >
            <thead>
                <tr role="row">
                    <th class="w-64">#</th>
                    <th class="">Nome</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cids AS $key => $row)
                    <?php
                        $checked = array_key_exists($key, $guia_cids) ? 'checked' : null;
                    ?>
                    <tr>
                        <td>
                            <input type="checkbox" {{ $checked }} class="btn-checked-guia-cids" id-cid="{{$key}}" id-guias="{{$guia}}"/>
                        </td>
                        <td class='row_td_cid'>{{$row}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

       
        <?php
    else:
        echo "<div class='panel bg-danger pos-rlt'>
                <span class='arrow top  b-danger '></span>
                <div class='panel-body'>".Lang::get('grid.nenhum-registro-encontrado')."</div>
              </div>";
    endif;
?>