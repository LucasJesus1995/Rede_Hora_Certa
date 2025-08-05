<?php
    if(!empty($grid)):
        ?>

        <table class="table table-striped table-responsive table-bordered  bg-light " >
            <thead>
                <tr role="row">
                    <th class="w-64">#</th>
                    <th class="w">Valor</th>
                    <th>Medicamento</th>
                </tr>
            </thead>
            <tbody>
                @foreach($grid AS $row)
                    <?php
                        $checked = ($row['default']) ? 'checked' : null;
                        $valor = !empty($row['valor']) ? $row['valor'] : null;
                    ?>
                    <tr class="">
                        <td>
                            <input type="checkbox" name="default_{{$row['id']}}" id="default_{{$row['id']}}" class="btn-checked-medicamento-linha-cuidado" rel="{{$row['id']}}" {{$checked}} />
                        </td>
                        <td nowrap>
                            {!!Form::textField('valor_'.$row['id'], null, $valor, array('class' => 'form-control btn-checked-medicamento-linha-cuidado','rel'=> $row['id'],'id'=>'valor_'.$row['id']))!!}
                        </td>
                        <td>{{$row['nome']}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

       <input type="hidden" id="url-pagination" value="{{urldecode($_SERVER['REQUEST_URI'])}}" />
        <?php
    else:
        echo "<div class='panel bg-danger pos-rlt'>
                <span class='arrow top  b-danger '></span>
                <div class='panel-body'>".Lang::get('grid.nenhum-registro-encontrado')."</div>
              </div>";
    endif;
?>