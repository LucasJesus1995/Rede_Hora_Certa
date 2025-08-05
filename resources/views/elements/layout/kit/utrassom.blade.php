<?php
$questionario = \App\AnamnesePerguntas::Questionario(9);
?>

<div class="bloco">
    @if($agenda->linha_cuidado == 7)
        <h2>ULTRASSONOGRAFIA</h2>
    @else
        <h2>DOPPLER</h2>
    @endif
    <div class="content">
        <div class="bloco" style=" margin: 5px;">
            <h2>Laudo</h2>
            <hr style="margin-top: 22px"/>
            <hr style="margin-top: 22px"/>
            <hr style="margin-top: 22px"/>
            <hr style="margin-top: 22px"/>
        </div>

        <div style="margin: 10px">
            <table width="100%" cellspacing="0" cellpadding="1" class="no-border">
                @foreach($questionario AS $p)
                    <?php $cor = (@$cor == null) ? "url('/src/image/bg-cinza-grid.jpg')" : null;?>
                    <tr style="background: {{$cor}}">
                        <td width="40%"><h3>{{$p['nome']}}</h3></td>
                        <td width="60%">
                            <?php
                            $resposta = \App\Http\Helpers\Anamnese::MountASKPrint($p['tipo_resposta']);

                            if (!empty($resposta)) {
                                $ln = 0;
                                foreach ($resposta AS $k => $r) {

                                    if (is_numeric($k)) {
                                        $ln += 1;

                                        echo "<li style='width: 33%; float: left; margin: 0; padding: 0; list-style: none; line-height: 20px;'>&nbsp;&nbsp;(&nbsp;&nbsp;&nbsp;&nbsp;) {$r}</li>";
                                        if ($ln == 3) {
                                            echo "<span style='clear: both'></span>";
                                            $ln = 0;
                                        }

                                    } else {
                                        $r = (in_array(trim($r), array('Resposta Generica', 'Ultima Consulta', 'Outros', 'Outras', 'Outro', 'Outra', 'Descrição', 'Data ultima crise'))) ? "&nbsp;" : $r;
                                        $r = (in_array(trim($r), array('Qual(is)?'))) ? "&nbsp;" : $r;
                                        if ($ln == 2)
                                            echo "<li style='clear: both; float: left; margin: 0; padding: 0; list-style: none'>
                                            <div style='border-bottom: 1px solid #000 !important; margin-left: 4px; '>{$r} </div>
                                          </li>";
                                        else
                                            echo "<div style='clear: both; border-bottom: 1px solid #000 !important; margin-left: 4px; '>{$r} </div>";
                                    }

                                }
                                echo "<span style='clear: both'></span>";
                            }

                            ?>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>

<div>Declaro veracidade das informações e qeu esclareci todas as minhas dúvidas.</div>

<table width="100%" style="margin-top: 60px">
    <tr>
        <td width="50%">
            <div style="text-align: center !important;">
                <hr style="width: 270px; margin: 0 auto"/>
                Assinatura do paciente ou responsável
            </div>
        </td>
        <td width="50%">
            <div style="text-align: center !important;">
                <hr style="width: 270px; margin: 0 auto"/>
                Enfermagem / Carimbo
            </div>
        </td>
    </tr>
</table>