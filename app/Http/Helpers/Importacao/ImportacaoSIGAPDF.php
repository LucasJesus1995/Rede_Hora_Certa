<?php
/**
 * Created by PhpStorm.
 * User: edersonsandre
 * Date: 2019-05-03
 * Time: 16:57
 */

namespace App\Http\Helpers\Importacao;


use App\Estabelecimento;
use App\Http\Helpers\Util;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Process\Process;

class ImportacaoSIGAPDF
{

    public static function import($pdf)
    {
        $data = [];

        $paginas = $pdf->getPages();

        $_rows = [];
        $reservadas['Atend'] = null;
        $reservadas['Agenda'] = null;
        $reservadas['Hora'] = null;
        $reservadas['Paciente'] = null;
        $reservadas['Procedimento'] = null;
        $reservadas['Tipo'] = null;
        $reservadas['Grupo'] = null;
        $reservadas['Diagnóstico'] = null;
        $reservadas['Procedimentos'] = null;
        $reservadas['PRIMEIRA'] = null;
        $reservadas['VEZ'] = null;
        $reservadas['RETORNO'] = null;

        $i = 0;
        foreach ($pdf->getPages() as $page) {
            $rows = $page->getTextArray();

            $_horario = null;
            foreach ($rows as $k => $row) {

                if (empty($row)) {
                    unset($paginas[$k]);
                    continue;
                }

                if (array_key_exists($row, $_rows)) {
                    unset($paginas[$k]);
                    continue;
                }

                if (array_key_exists($row, $reservadas)) {
                    unset($paginas[$k]);
                    continue;
                }

                if (strlen($row) == 15 && strlen(Util::somenteNumeros($row)) == 15) {
                    $nome = $rows[$k - 1];

                    if (count(explode(" ", $nome)) == 1) {
                        $nome = $rows[$k - 2] . " " . $rows[$k - 1];
                    }

                    $ubs = null;
                    foreach (range(-7, 2) as $i) {
                        $line = $rows[$k + $i];
                        if ($ubs != null) {
                            break;
                        }

                        if (strstr($line, 'Unidade Solicitante')) {
                            $ubs = trim(str_replace("Unidade Solicitante:", "", $line));

                            if (!in_array($rows[$k + $i + 1], ["PRIMEIRA"])) {
                                $ubs .= " " . $rows[$k + $i + 1];
                            }
                        }
                    }

                    $horario = self::_getHorarioImportPDF($rows, $k);


                    try {
                        $estabelecimento = !empty($ubs) ? Estabelecimento::getEstabelecimentoSaveByNome($ubs)->id : null;
                    } catch (\Exception $e) {
                        $estabelecimento = null;
                    }

                    $data[] = [
                        'nome' => $nome,
                        'horario' => strlen($horario) == 5 ? $horario : $data[count($data) - 1]['horario'],
                        'cns' => $row,
                        'estabelecimento' => $estabelecimento,
                    ];

                    $i++;
                }
            }
        }

        return $data;
    }

    private static function _getHorarioImportPDF($rows, $key)
    {
        for ($i = $key, $i > 0; $i--;) {
            $data = Util::somenteNumeros($rows[$i]);

            if (strlen($data) == 4 && preg_match('/^[0-9]{2}:[0-9]{2}$/', $rows[$i])) {
                return $rows[$i];
                break;
            }
        }
    }

}