<?php
    if(!empty($linha_cuidado)):
        ?>
        <table class="table table-striped table-responsive table-bordered  bg-light " >
            <thead>
                <tr role="row">
                    <th class="w-64">#</th>
                    <th class="">nome</th>
                </tr>
            </thead>
            <tbody>
                @foreach($linha_cuidado AS $row)
                    <?php
                        $checked = array_key_exists($row['id'], $linha_cuidado_programa) ? 'checked' : null;
                    ?>
                    <tr>
                        <td>
                            <input type="checkbox" {{ $checked }} class="btn-checked-programa-linha-cuidado" id-linha-cuidado="{{$row['id']}}" id-programa="{{$programa}}"/>
                        </td>
                        <td>{{$row['nome']}}</td>
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