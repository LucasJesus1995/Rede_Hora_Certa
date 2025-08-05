<?php

namespace App;

use App\Http\Helpers\Upload;
use App\Http\Helpers\Util;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;


class Importacao extends Model
{

    public $_log = array();
    public $_error = false;
    private $logger = array();

    public function setLog($message)
    {
        $this->logger[] = date('H:i:s') . ' - ' . $message;
    }

    public function getLog()
    {
        return $this->logger;
    }

    public function destroyLog()
    {
        $this->logger = array();
    }

    private function getPacientes($data, $line)
    {

        if (strlen(trim($data[4])) < 14) {
            $this->setLog("CNS {$data[4]} inválido! O cartão CNS deverá ter no minimo 14 posicoes");
            throw new \Exception("CNS {$data[4]} inválido! O cartão CNS deverá ter no minimo 14 posicoes");
        }

        $paciente = Pacientes::getByCNS(trim($data[4]));
        if ($paciente) {
            $this->setLog("Paciente já esta cadastrado");

            return $paciente->id;
        }

        $paciente['estabelecimento'] = $this->getEstabelecimento(trim($data[3]));
        $paciente['cns'] = trim($data[4]);
        $paciente['nome'] = trim($data[1]);
        $paciente['mae'] = trim($data[5]);
        $paciente['nascimento'] = $data[6];
        $paciente['sexo'] = self::getSexo($data[7]);
        $paciente['nacionalidade'] = self::getNacionalidade($data[8]);
        $paciente['raca_cor'] = self::getRacaoCor($data[9]);
        $paciente['celular'] = $data[10];
        $paciente['telefone_residencial'] = trim($data[11]);
        $paciente['telefone_comercial'] = trim($data[12]);
        $paciente['email'] = trim($data[13]);
        $paciente['contato'] = trim($data[14]);
        $paciente['cep'] = trim($data[15]);
        $paciente['cidade'] = $this->getCidade($data[16]);
        $paciente['endereco_tipo'] = self::getMunicipio($data[17]);
        $paciente['endereco'] = trim($data[18]);
        $paciente['numero'] = trim($data[19]);
        $paciente['bairro'] = trim($data[20]);
        $paciente['complemento'] = trim($data[21]);
        $paciente['cpf'] = trim($data[22]);
        $paciente['rg'] = trim($data[23]);
        $paciente['import'] = $line;

        $__paciente = new Pacientes();
        $__paciente->saveData($paciente);

        $this->setLog("Paciente cadastrado com sucesso");
        $_paciente = Pacientes::getByCNS(trim($data[4]))->id;

        $this->setLog("Recuperando os dados do paciente por CNS {$data[4]} e data de nasciento {$data[6]}");

        if (empty($_paciente)) {
            $this->setLog("Paciente não encontrado por CNS {$data[4]}");
            throw new \Exception("Paciente não encontrado!");
        }

        return !empty($_paciente) ? $_paciente : null;
    }

    public function importaAgenda($data)
    {
        $importado = array(
            'sucesso' => 0,
            'error' => 0
        );

        try {
            $_upload = new Upload();

            $filename = $_upload->importacao_upload_agenda($data['file']);

            $importacao_agenda = new ImportacaoAgenda();
            $importacao_agenda->file = $filename;
            $importacao_agenda->user = Auth::user()->id;
            $importacao_agenda->save();

            $content = File::get(PATH_UPLOAD . "importacao/agenda/" . $filename);

            if (!empty($content)) {

                $content = explode("\n", $content);

                if (is_array($content)) {
                    $params = $data->all();
                    unset($params['file']);
                    $this->_log['params'] = $params;

                    $this->_log['params']['file'] = PATH_UPLOAD . "importacao/agenda/" . $filename;

                    foreach ($content AS $line_number => $line) {
                        if (empty($line)) {
                            break;
                        }

                        try {
                            $this->setLog("Dados dos Paciente: {$line}");
                            $_data = explode(";", $line);

                            $procedimento = $this->getProcedimentos($_data[2]);
                            if (!$procedimento) {
                                throw new \Exception("Procedimento  {$_data[2]} não encontradoa!");
                            }

                            $estabelecimento = $this->getEstabelecimento($_data[3], $importacao_agenda->id . '-' . ($line_number + 1));

                            $paciente = $this->getPacientes($_data, $importacao_agenda->id . '-' . ($line_number + 1));
                            if (!$paciente) {
                                throw new \Exception("Paciente  {$_data[4]} não foi possivel cadastrar em encontrar no banco de dados!");
                            }

                            $agenda = [];
                            $agenda['data'] = $data['data'];
                            $agenda['hora'] = $_data[0];
                            $agenda['paciente'] = $paciente;
                            $agenda['tipo_atendimento'] = $data['tipo_atendimento'];
                            $agenda['arena'] = $data['arena'];
                            $agenda['linha_cuidado'] = $data['linha_cuidado'];
                            $agenda['medico'] = $data->get('medico');
                            $agenda['arena_equipamento'] = (!empty($data['equipamento']) && $data['equipamento'] != 0) ? $data['equipamento'] : null;
                            $agenda['procedimento'] = $procedimento;
                            $agenda['estabelecimento'] = $estabelecimento;
                            $agenda['import'] = $importacao_agenda->id . '-' . ($line_number + 1);

                            if ($check = $this->isPacienteAgenda($paciente, $agenda['data'] . " " . $_data[0] . ":00")) {
                                throw new \Exception("Paciente  {$_data[4]} já está com este o horario em outra agenda!");
                            }

                            $this->setLog("Iniciando processo de gravacao da agenda");

                            $__agenda = new Agendas();
                            if ($__agenda->saveData($agenda)) {
                                $importado['sucesso']++;
                                $this->setLog("Registro importado com sucesso!");
                            } else {
                                $this->setLog("OPSS! Falha ao importar registro");
                                throw new \Exception("Não será possivel cadastrar o paciente {$_data[4]} na agenda!");
                            }

                        } catch (\Exception $e) {
                            $this->_log['agenda'][trim($_data[4])]['data'] = $_data;
                            $this->_log['agenda'][trim($_data[4])]['log'][] = $this->getLog();
                            $this->_log['agenda'][trim($_data[4])]['error'][] = $e->getMessage();
                            // $this->_log['agenda'][trim($_data[4])]['error'][] = $e->getTraceAsString();
                            $this->_error = true;
                            $importado['error']++;
                        }

                        $this->destroyLog();
                    }
                } else {
                    throw new \Exception("Arquivo vazio!");
                }

                $importacao_agenda->imported = ($importado['sucesso']);
                $importacao_agenda->failure = ($importado['error']);
                $importacao_agenda->records = (($importado['error']) + ($importado['sucesso']));
                $importacao_agenda->data = serialize($this->_log['params']);
                $importacao_agenda->save();

            } else {
                throw new \Exception("Arquivo vazio ou não encontrado!");
            }

        } catch (\Exception $e) {
            $this->_error = true;
            $this->_log['error'][] = $e->getMessage();
            $importado['error']++;
        }

        $file = Util::ForceContent(PATH_UPLOAD . "importacao/agenda/log/", $filename, serialize($this->_log));

        self::_sendS3($file, $importacao_agenda->id . '-log.txt');

        if (file_exists(PATH_UPLOAD . "importacao/agenda/" . $filename)) {
            self::_sendS3(PATH_UPLOAD . "importacao/agenda/" . $filename, $importacao_agenda->id . '.txt');
        }
    }

    public static function getRacaoCor($key)
    {
        $data['BRANCA'] = 1;
        $data['PRETA'] = 2;
        $data['PARDA'] = 3;
        $data['AMARELA'] = 4;
        $data['INDIGENA'] = 5;
        $data['SEM INFORMACAO'] = 99;

        return array_key_exists($key, $data) ? $data[$key] : 99;
    }

    public static function getNacionalidade($key)
    {
        $data['Brasileira'] = 10;
        $data['Brasileira (Naturalizado)'] = 20;
        $data['Outros'] = 50;

        return array_key_exists($key, $data) ? $data[$key] : 50;
    }

    public static function getSexo($key)
    {
        $data['Feminino'] = 2;
        $data['Masculino'] = 1;

        return array_key_exists($key, $data) ? $data[$key] : null;
    }

    public static function getMunicipio($key)
    {
        $data['RUA'] = 81;
        $data['AV'] = 8;
        $data['TRAVESSIA'] = 100;
        $data['ESTRADA'] = 31;

        return array_key_exists($key, $data) ? $data[$key] : 81;
    }

    public function getCidade($cidade)
    {
        $key = 'get-cidade-importacao-' . snake_case(Util::RemoveAcentos($cidade));

        $this->setLog("Pesquisando cidade");

        if (!Cache::has($key)) {
            $cidade = Cidades::where('nome', $cidade)->first();

            if (!empty($cidade->id) && count($cidade->id)) {
                Cache::put($key, $cidade, CACHE_DAY);
                $this->setLog("- Cadastrando cidade no cache");
            } else {
                $this->setLog("- Cidade {$cidade} não encontrada!");
                throw new \Exception("Cidade {$cidade} não cadastrada");
            }
        } else {
            $cidade = Cache::get($key);
            $this->setLog("- Recuperando informacoes da cidade do cache");
        }

        return !empty($cidade->id) ? $cidade->id : 5271;
    }

    public function getEstabelecimento($nome, $line = null)
    {
        $key = 'get-estabelecimento-importacao-' . snake_case(Util::RemoveAcentos($nome));

        $this->setLog("Pesquisando estabelecimento!");

        if (!Cache::has($key)) {
            $estabelecimento = Estabelecimento::where('nome', trim($nome))->get();

            if (empty($estabelecimento[0]) || empty($estabelecimento[0]->id)) {
                $this->setLog("- Estabelecimento não cadastrado!");

                $_model = new Estabelecimento();
                $_model->nome = trim($nome);
                $_model->import = $line;
                $_model->save();

                if (!empty($_model->id)) {
                    $this->setLog("- Cadastrado novo estabelecimento!");
                    Cache::put($key, $_model, CACHE_DAY);

                    return $_model->id;
                } else {
                    $this->setLog("- Não foi possivel cadastrar o estabelecimento!");
                    throw new \Exception("Não foi possivel cadastrar o estabelecimento!");
                }
            } else {
                Cache::put($key, $estabelecimento[0], CACHE_DAY);
                $this->setLog("- Recuperando dados dos cache");

                return $estabelecimento[0]->id;
            }

        } else {
            $this->setLog("- Recuperando informacoes do estabelecimento do cache");
            $estabelecimento = Cache::get($key);
        }

        return $estabelecimento->id;
    }


    public function getProcedimentos($nome)
    {
        $key = 'get-procedimento-importacao-' . snake_case(Util::RemoveAcentos($nome));

        $this->setLog("Pesquisando procedimento");

        if (!Cache::has($key)) {
            $procedimentos = Procedimentos::where('nome', utf8_encode(trim($nome)))->where('ativo', 1)->get();

            if (!empty($procedimentos[0]->id)) {
                Cache::put($key, $procedimentos[0], CACHE_DAY);
                $this->setLog("- Procedimento cadastrado");

                return $procedimentos[0]->id;
            } else {
                $this->setLog("- Procedimento não cadastrado!");

                throw new \Exception("Procedimento não encontrado!");
            }
        } else {
            $procedimentos = Cache::get($key);
            $this->setLog("- Procedimento recuperado do cache!");
        }

        return ($procedimentos) ? $procedimentos->id : null;
    }


    public function isPacienteAgenda($paciente = null, $data)
    {
        if (!$paciente) {
            return false;
        }

        $this->setLog("Verificando se o paciente está cadastrado em alguma agenda");

        $agenda = Agendas::where('paciente', $paciente)->where('data', '=', Util::Timestamp2DB($data))->get();

        $this->setLog("Verificando se o paciente está cadastrado em alguma agenda neste horario");
        if (!empty($agenda[0])) {
            $this->setLog("- Paciente já cadastrado em agenda");
            return true;
        } else {
            $this->setLog("- Paciente não está cadastrado em agenda");
            return false;
        }
    }

    private function _sendS3($arquivo, $new_file)
    {
        $path_remote = "cies-sistema/importacao/agenda/" . date('Y') . "/" . date('m') . "/" . date('d') . "/";

        if (Storage::disk('s3')->put($path_remote . $new_file, file_get_contents($arquivo))) {
            //unlink($arquivo);
        }
    }
}