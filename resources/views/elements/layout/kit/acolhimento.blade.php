<?php
$questionario = \App\AnamnesePerguntas::Questionario(2);
?>

<div class="bloco">
    <h2>FICHA DE ACOLHIMENTO</h2>
    <div class="content">
        <table width="100%" cellspacing="0" cellpadding="1" class="no-border">
            @foreach($questionario AS $p)
                <?php $cor_list = (@$cor_list == "even") ? "odd" : "even";?>
                <tr class="{!! $cor_list !!}">
                    <td width="40%"><h3>{{$p['nome']}}</h3></td>
                    <td width="60%">
                        <?php
                        $resposta = \App\Http\Helpers\Anamnese::MountASKPrint($p['tipo_resposta']);
                        $ln = 0;
                        if ($resposta) {
                            foreach ($resposta AS $k => $r) {

                                if (is_numeric($k)) {
                                    $ln += 1;

                                    echo "<li style='width: 33%; float: left; margin: 0; padding: 0; list-style: none'>&nbsp;&nbsp;(&nbsp;&nbsp;&nbsp;&nbsp;) {$r}</li>";
                                    if ($ln == 3) {
                                        echo "<span style='clear: both'></span>";
                                        $ln = 0;
                                    }

                                } else {
                                    $r = (in_array(trim($r), array('Resposta Generica', 'Ultima Consulta', 'Outros', 'Outras', 'Outro', 'Outra', 'Descrição', 'Data ultima crise'))) ? "&nbsp;" : $r;
                                    $r = (in_array(trim($r), array('Qual(is)?'))) ? "&nbsp;" : $r;
                                    if ($ln == 2)
                                        echo "<li style='width: 33%; float: left; margin: 0; padding: 0; list-style: none'>
                                            <div style='border-bottom: 1px solid #000 !important; margin-left: 4px; '>{$r} </div>
                                          </li>";
                                    else
                                        echo "<div style='border-bottom: 1px solid #000 !important; margin-left: 4px; '>{$r} </div>";
                                }
                            }
                            echo "<span style='clear: both'></span>";
                        } else {
                        }
                        ?>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</div>