<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TraitController;
use App\Http\Helpers\AtendimentoHelpers;
use App\Http\Helpers\Exportacao\ExportacaoArquivosHelpers;
use App\Http\Helpers\Upload;
use App\Http\Helpers\Util;
use App\LinhaCuidado;
use App\Pacientes;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;
use stdClass;


class ExportacaoController extends Controller
{
    use TraitController;

    public function __construct()
    {
        $this->title = "app.exportacao";


        parent::__construct();
    }

    public function getArquivos()
    {
        $view = View("admin.exportacao.arquivos.index")->with('title', 'Exportação de arquivos');

        $view->lists = [
            'arenas' => 'Arenas',
            'especialidades' => 'Especialidades',
            'usuarios' => 'Usuários do sistema',
            'procedimentos' => 'Procedimentos',
        ];

        return $view;
    }

    public function postArquivos($key)
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 120);

        $link = ExportacaoArquivosHelpers::getFile($key);

        $html = '<div class="alert alert-danger text-left">Não foi possivel criar o arquivo.</div>';
        if (!empty($link)) {
            $html = '<div class="alert alert-success text-left"><a href="' . $link . '" target="_blank" ><strong>Clique aqui</strong></a> para fazer download do arquivo.</div>';
        }

        return $html;

    }

    public function getPacientes()
    {
        $view = View('admin.exportacao.pacientes.index');

        return $view;
    }

    public function postPacientes()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 600);

        $params = Input::all();

        $params['arena'] = 1;

        try {

            $sql = "CREATE TEMPORARY TABLE tmp_exportacao_pacientes (
                      nome VARCHAR (200) NULL,
                      cns VARCHAR (200) NULL,
                      cpf VARCHAR (200) NULL,
                      rg VARCHAR (200) NULL,
                      nascimento VARCHAR (200) NULL,
                      sexo VARCHAR (200) NULL,
                      estado_civil VARCHAR (200) NULL,
                      raca_cor VARCHAR (200) NULL,
                      cep VARCHAR (200) NULL,
                      endereco VARCHAR (200) NULL,
                      numero VARCHAR (200) NULL,
                      bairro VARCHAR (200) NULL,
                      endereco_tipo VARCHAR (200) NULL,
                      created_at VARCHAR (200) NULL,
                      estabelecimento VARCHAR (200) NULL,
                      id VARCHAR (200) NULL,
                      data VARCHAR (200) NULL,
                      arena VARCHAR (200) NULL,
                      linha_cuidado VARCHAR (200) NULL,
                      procedimento VARCHAR (200) NULL,
                      atendimento_data VARCHAR (200) NULL,
                      atendimento_id VARCHAR (200) NULL,
                      atendimento_preferencial VARCHAR (200) NULL,
                      atendimento_sala VARCHAR (200) NULL,
                      atendimento_medico VARCHAR (200) NULL,
                      medico VARCHAR (200) NULL
                    ) DEFAULT CHARSET=utf8;";

            $results = DB::select($sql);

            $periodo = explode("-", $params['periodo']);

            if (count($periodo) != 2) {
                throw new \Exception("Periodo inválido ou não existente");
            }

            $mes = $periodo[1];
            $ano = $periodo[0];

            $ultimo_dia = Util::getUltimosDiaMes($ano, $mes);

            $data_inicial = "{$ano}-{$mes}-01 00:00:00";
            $data_final = "{$ano}-{$mes}-{$ultimo_dia} 23:59:59";

            $sql_insert = " SELECT 
                                pacientes.nome,
                                pacientes.cns,
                                pacientes.cpf,
                                pacientes.rg,
                                pacientes.nascimento,
                                pacientes.sexo,
                                pacientes.estado_civil,
                                pacientes.raca_cor,
                                pacientes.cep,
                                pacientes.endereco,
                                pacientes.numero,
                                pacientes.bairro,
                                pacientes.endereco_tipo,
                                pacientes.created_at,
                                agendas.id,
                                agendas.data,
                                arenas.nome AS arena,
                                linha_cuidado.nome AS linha_cuidado
                            FROM pacientes
                              JOIN agendas ON agendas.paciente = pacientes.id
                              JOIN arenas ON arenas.id = agendas.arena
                              JOIN linha_cuidado ON linha_cuidado.id = agendas.linha_cuidado
                            WHERE
                              agendas.data BETWEEN '{$data_inicial}' AND '{$data_final}'
                              AND agendas.arena = {$params['arena']}
                            ;
            ";

            $sql = "INSERT INTO tmp_exportacao_pacientes (nome, cns, cpf, rg, nascimento, sexo, estado_civil, raca_cor, cep, endereco, numero, bairro, endereco_tipo,created_at, id, data, arena, linha_cuidado) {$sql_insert};";
            DB::select($sql);

            $sql = "UPDATE tmp_exportacao_pacientes as a JOIN atendimento ON a.id = atendimento.agenda
                    SET 
                      a.atendimento_data =  atendimento.created_at,
                      a.atendimento_id =  atendimento.id,
                      a.atendimento_medico =  atendimento.medico,
                      a.atendimento_preferencial = atendimento.preferencial,
                      a.atendimento_sala =  atendimento.sala";
            DB::select($sql);

            $sql = "UPDATE tmp_exportacao_pacientes as a JOIN procedimentos ON a.procedimento = procedimentos.id
                    SET 
                      a.procedimento =  procedimentos.nome";
            DB::select($sql);

            $sql = "UPDATE tmp_exportacao_pacientes as a JOIN profissionais ON a.atendimento_medico = profissionais.id
                    SET 
                      a.medico =  profissionais.nome";
            DB::select($sql);

            $filename = "cies-pacientes";

            $path = PATH_FILE_RELATORIO . 'excel/exportacao/pacientes/' . Util::getUser() . '/';
            Upload::recursive_mkdir($path);

            $sql = "SELECT * FROM tmp_exportacao_pacientes INTO OUTFILE '/tmp/pacientes-2.csv' FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\n'";
            echo $sql;
            echo "<br />";
            echo "<br />";
            flush();
            $data = DB::select($sql);

            die;

            $txt = [];
            foreach ($data as $row) {
                $txt[] = implode(";", (array)$row);
            }

            echo implode("\n", $txt);
            die;

            $download = '//' . $_SERVER['SERVER_NAME'] . '/' . $path . $filename . '.xlsx';

            $return = [
                'success' => true,
                'download' => $download,
                'html' => "<div class='alert alert-success align-center'><a href='{$download}' id='btn-download-arquivo-exportacao-pacientes'  target='_blank'>CLIQUE AQUI PARA FAZER DOWNLOAD DO ARQUIVO!</a></div>"
            ];
        } catch (\Exception $e) {
            exit("<pre>LINE: " . __LINE__ . " - " . print_r($e->getMessage(), 1) . "</pre>"); #debug-edersonsandre

            $return = [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }

        return $return;
    }

    public function getKitImpressaoAvulso(){
        $view = View("admin.exportacao.kit-impressao-avulso.index")->with('title', 'Kit Impressão (Avulso)');

        $view->kits = AtendimentoHelpers::getKitsAvulso();

        return $view;
    }

    public function getKitImpressaoAvulsoDownload($id){

        try {
            $view = View("admin.exportacao.kit-impressao-avulso.impressao")->with('kit_white', true)->with('title', 'Kit Impressão (Avulso)');
            $view->kit = $id;

            $agenda = new stdClass();
            $agenda->id = -1;
            $agenda->linha_cuidado = null;
            $agenda->tipo_atendimento = null;

            $view->agenda = $agenda;

            $contents = $view->render();

            $dompdf = new Dompdf();
            $dompdf->loadHtml($contents, 'UTF-8');
            $dompdf->setPaper('A4');
            $dompdf->render();

            $nome_arquivo = "kit-impressao-avulso-" . $id;
            $dompdf->stream($nome_arquivo, array("Attachment" => false));

            die;
        } catch (\Exception $e) {
            print("<pre>" . print_r($e->getFile(), 1) . "</pre>");
            print("<pre>" . print_r($e->getLine(), 1) . "</pre>");
            exit("<pre>" . print_r($e->getMessage(), 1) . "</pre>");
            $view->messagem_error = $e->getMessage();
        }
    }

    public function getKitImpressao()
    {
        $view = View("admin.exportacao.kit-impressao.index")->with('title', 'Kit Impressão (em branco)');

        $view->especialidades = LinhaCuidado::select(['id', 'abreviacao', 'nome', 'especialidade'])->where('ativo', 1)->orderBy('nome', 'asc')->get();

        return $view;
    }


    public function getKitImpressaoDownload($especialidade)
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', "60");

        $params  = explode("-",$especialidade);
        $especialidade = $params[0];
        $sub_especialidade = !empty($params[1]) ?  $params[1] : null;

        $especialidade = LinhaCuidado::get($especialidade);
        if (!empty($especialidade['id'])) {

            try {
                $view = View("admin.agendas.kit-impressao")->with('kit_white', true)->with('title', '');
                $view->local = null;
                $view->sub_especialidade = $sub_especialidade;

                $agenda = new stdClass();
                $agenda->id = -1;
                $agenda->linha_cuidado = $especialidade['id'];
                $agenda->tipo_atendimento = null;

                $view->agenda = $agenda;

                $contents = $view->render();

                $dompdf = new Dompdf();
                $dompdf->loadHtml($contents, 'UTF-8');
                $dompdf->setPaper('A4');
                $dompdf->render();

                $nome_arquivo = "kit-impressao-" . strtolower($especialidade['abreviacao']);
                $dompdf->stream($nome_arquivo, array("Attachment" => false));

                die;
            } catch (\Exception $e) {
                print("<pre>" . print_r($e->getFile(), 1) . "</pre>");
                print("<pre>" . print_r($e->getLine(), 1) . "</pre>");
                exit("<pre>" . print_r($e->getMessage(), 1) . "</pre>");
                $view->messagem_error = $e->getMessage();
            }

            return $view;
        }
    }

}