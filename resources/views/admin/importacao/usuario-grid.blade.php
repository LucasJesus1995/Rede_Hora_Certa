<?php
    if(!empty($grid)):
        ?>
        <table class="table table-striped table-responsive table-bordered  bg-light " >
            <thead>
                <tr role="row">
                    <th>Data</th>
                    <th>Hora</th>
                    <th>Inserido</th>
                    <th>Atualizado</th>
                    <th>Total</th>
                    <th class="w-64">Arquivo</th>
                    <th class="w-64">Log</th>
                </tr>
        </thead>
            <tbody>
                @foreach($grid AS $key => $row)
                    <tr class="">
                        <td>{{$row['date']}}</td>
                        <td>{{$row['time']}}</td>
                        <td>{{$row['insert']}}</td>
                        <td>{{$row['update']}}</td>
                        <td>{{$row['total']}}</td>
                        <td nowrap>
                            @if(!empty($row['file']))
                                <a href="/admin/importacao/file?file={{$row['file']}}"  rel="{{$key}}" ><i class="fa fa-file"></i></a>
                            @endif
                        </td>
                        <td nowrap>
                            <a href="#"  rel="{{$key}}" class="box-line-relatorio"><i class="fa fa-file-code-o"></i></a>
                        </td>
                    </tr>
                    <tr id="box-line-{{$key}}" style="display: none">
                        <td colspan="100%"><pre style="font-size: 10px"><?php print_r(unserialize($row['log'])); ?></pre></td>
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