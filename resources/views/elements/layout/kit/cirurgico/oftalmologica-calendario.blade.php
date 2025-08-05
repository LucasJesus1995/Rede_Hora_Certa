<?php
if(!isset($kit_white) || !$kit_white){
    $paciente = (Object)\App\Pacientes::get($agenda->paciente);
}else{
    $agenda = new stdClass();
    $paciente = new stdClass();
    $paciente->nome_social = null;
    $paciente->nome = "PACIENTE";

    $agenda->id = null;
    $agenda->data = date('Y-m-d');
    $agenda->linha_cuidado = $linha_cuidado->id;
}
?>
@include('elements.layout.pdf.kit.header', array('agenda'=>$agenda->id, 'box' => false, 'logo_drsaude' => false, 'hidden_logo_header' => true))

<h1 class="title">ZYPRED ou VIGADEXA - APLICAR 01 GOTA SOMENTE NO OLHO OPERADO!</h1>

<?php
$data = $agenda->data;

$data_arr = explode("-", $data);

$data_primeiro_dia = "{$data_arr[0]}-{$data_arr[1]}-01";

$dia_semana = date('w', strtotime($data_primeiro_dia));
if ($dia_semana == 0) {
    $dia_semana = 1;
}

$init = false;

$horas[] = "1/1 hora";
$gotas = 2;
for ($i = 1; $i <= 28; $i++) {
    if (!in_array($gotas, [8, 10])) {
        $horas[] = "{$gotas}/{$gotas} horas";
        if (in_array($i / 7, [1, 2, 3, 4])) {
            $gotas += 2;
            if ($gotas == 8 || $gotas == 10 || $gotas == 14) {
                $gotas = 12;
            }
        }
    } else {
        $gotas += 2;
    }
}
$horas[] = "PARAR!";

$_horas = $horas;
?>
@include('elements.layout.kit.cirurgico.utils.calendario')

<div style="position: absolute; bottom: -50px">
    @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id])
</div>