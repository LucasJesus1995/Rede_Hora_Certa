<table class="border">
    <tr>
        <th class="title" colspan="7" style="background: #0e0e0e !important; color: #FFF">{!!  \App\Http\Helpers\Util::getMesNome($data_arr[1]) !!}</th>
    </tr>

    <tr>
        <th class="center">Domingo</th>
        <th class="center">Segunda</th>
        <th class="center">Terça</th>
        <th class="center">Quarta</th>
        <th class="center">Quinta</th>
        <th class="center">Sexta</th>
        <th class="center">Sábado</th>
    </tr>
    @for($ln = 1; $ln <= 5; $ln++)
        <tr>

            @for($col = 1; $col <= 7; $col++)
                <td width="14%">

                    <?php

                    if ($dia_semana == $col && $init === false) {
                        $init = 0;
                        $dia = \Carbon\Carbon::createFromDate($data_arr[0], $data_arr[1], 1, 0, 0, 0);
                    }

                    if ($init !== false && isset($dia) && $dia->month == $data_arr[1]) {
                        echo "<div  style='text-align: right title'>{$dia->day}</div>";

                        if (array_key_exists($init, $horas) && $dia->day >= $data_arr[2]) {
                            echo "<div  style='text-align: center; margin: 10px 0; font-size: 14px !important; text-align: center; font-weight: bolder'>{$horas[$init]}</div>";

                            unset($_horas[$init]);
                            $init += 1;

                        } else {
                            echo "<div style='height: 35px' ></div>";
                        }

                        $dia = $dia->addDay(1);
                    }
                    ?>
                    &nbsp;
                </td>
            @endfor
        </tr>
    @endfor
</table>

@if(count($_horas) > 0)
    <?php
    $horas = array_values($_horas);
    $_horas = null;
    $init = false;
    $data = $dia->toDateString();

    $data_arr = explode("-", $data);
    $data_primeiro_dia = "{$data_arr[0]}-{$data_arr[1]}-01";

    $dia_semana = date('w', strtotime($data_primeiro_dia));
    ?>
    <br/><br/>
    @include('elements.layout.kit.cirurgico.utils.calendario')

@endif
