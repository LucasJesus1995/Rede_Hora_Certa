<div>
    @include('elements.layout.pdf.kit.header', array('agenda'=>$agenda->id, 'box' => false, 'logo_drsaude' => false, 'hidden_logo_header' => true))

    <div class="bloco">
        <h2>FICHA DE ACOLHIMENTO</h2>
        <div class="content">
            <div><strong>Nos últimos 14 dias o(a) sr.(a) apresentou: </strong></div>
            <table width="100%" cellspacing="0" cellpadding="1" class="no-border">
                @foreach(\App\AnamnesePerguntas::Questionario(10) AS $p)
                    <?php $cor_list = (@$cor_list == "even") ? "odd" : "even";?>
                    <tr class="{!! $cor_list !!}">
                        <td width="40%" colspan="{!! $p['tipo_resposta'] > 0 ? 1 : 2 !!}"><h3>
                                &nbsp;&nbsp;&nbsp;{{$p['nome']}}</h3></td>
                        @if($p['tipo_resposta'] > 0)
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
                        @endif
                    </tr>
                @endforeach
            </table>
        </div>

        @if(!in_array($agenda->linha_cuidado, [23]))
            <div class="bloco odd padding5" style="margin: 3px">
                <strong>Observações:</strong>
                <ul style="margin-left: 5px">
                    <li>- Paciente que apresentar 01 resposta positiva deve ter o procedimento REAGENDADO;</li>
                    <li>- Paciente deve higienizar as mãos com água e sabão ou com álcool 70% antes de iniciar o seu
                        atendimento;
                    </li>
                    <li>- Paciente deve procurar UBS mais próxima quando apresentar sintomas gripais leves, e se
                        apresentarem sintomas graves, deve ser encaminhado a um Pronto Atendimento pela Equipe do SAMU.
                    </li>
                </ul>
            </div>
        @endif

        <table width="100%" cellspacing="0" cellpadding="1" class="no-border">
            <?php
            $key = 11;
            if ($agenda->linha_cuidado == 47) {
                $key = 12;
            }

            if (empty($agenda->linha_cuidado)) {
                if (!empty($kit) && $kit == 5) {
                    $key = 13;
                }
            }

            if (in_array($agenda->linha_cuidado, [23, 27])) {
                $key = $agenda->linha_cuidado;
            }

            ?>

            @foreach(\App\AnamnesePerguntas::Questionario($key) AS $p)
                <?php $cor_list = (@$cor_list == "even") ? "odd" : "even";?>
                <tr class="{!! $cor_list !!}">
                    <td width="40%" colspan="{!! $p['tipo_resposta'] > 0 ? 1 : 2 !!}"><h3>
                            &nbsp;&nbsp;&nbsp;{{$p['nome']}}</h3></td>
                    @if($p['tipo_resposta'] > 0)
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
                    @endif
                </tr>
            @endforeach
        </table>
    </div>
    <div style="margin-top: 0px; font-size: 10px !important; text-align: justify; font-style: italic;">
        @include('elements.layout.kit.aux.lei-lgpd')
    </div>
    <div style="margin-top: 5px">Declaro veracidade das informações e que esclareci todas as minhas dúvidas.</div>

    <div style="margin-top: 50px">
        @include('kit-impressao.cirurgico.default.assinaturas.paciente_enfermagem')
    </div>

    <div style="position: absolute; bottom: -50px">
        @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id, 'info_lgpd' => 1])
    </div>
</div>