<?php


namespace App\Http\Rules\Ofertas;


use App\ArenaEquipamentos;
use App\Arenas;
use App\Http\Helpers\DataHelpers;
use App\Http\Helpers\Util;
use App\LinhaCuidado;
use App\Ofertas;
use App\Profissionais;
use App\Usuarios;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Stmt\DeclareDeclare;

class ImportacaoExcelRules
{

    /**
     * @var array
     */
    private $profissionais;
    private $natureza;
    private $unidades;
    private $especialidades;
    private $equipamentos;
    private $periodo;
    private $status;
    /**
     * @var array
     */
    protected $data;
    protected $error = null;

    public function __construct()
    {
        $this->loadParams();

    }

    private function loadParams()
    {
        $this->profissionais = Profissionais::Combo()['Médico'];
        $this->unidades = Arenas::Combo();
        $this->natureza = DataHelpers::getNatureza();
        $this->especialidades = LinhaCuidado::Combo();
        $this->equipamentos = ArenaEquipamentos::getAllComboByArena();
        $this->periodo = DataHelpers::getPeriodo();
        $this->status = DataHelpers::getOfertaStatus();
    }

    protected function exception($message)
    {
        throw new \Exception($message);
    }

    public function clearRow(array $data)
    {
        $this->data = $data;

        $row = null;

        try {
            if (!empty($this->data['estabelecimento']) && $this->data['especialidade']) {
                $row['id'] = $this->getId();
                $row['user'] = Auth::user()->id;
                $row['codigo'] = !empty($data['id']) ? $data['id'] : $this->exception("ID não encontrado!");
                $row['arena'] = $this->getEstabelecimento();
                $row['linha_cuidado'] = $this->getEspecialidade();
                $row['profissional'] = $this->getProfissional();
                $row['equipamento'] = $this->getEquipamentos();
                $row['periodo'] = $this->getPeriodo();
                $row['status'] = $this->getStatus();
                $row['natureza'] = $this->getNatureza();
                $row['data'] = is_object($data['data_atendimento']) && !is_null($data['data_atendimento']) ? $data['data_atendimento']->format("Y-m-d") : $this->exception("DATA ATENDIMENTO {$data['data_atendimento']}  inválida!");
                $row['data_aprovacao'] = is_object($data['data_aprovacao']) && !is_null($data['data_aprovacao']) ? $data['data_aprovacao']->format("Y-m-d") : "";
                $row['hora_inicial'] = !empty($data['hora_inicio']) && !is_null($data['hora_inicio']) ? $data['hora_inicio']->format("H:i:s") : $this->exception(" HORARIO INICIO {$data['hora_inicio']} não é um horario válido!");
                $row['hora_final'] = !empty($data['hora_fim']) && !is_null($data['hora_fim']) ? $data['hora_fim']->format("H:i:s") : $this->exception("HORARIO FIM {$data['h_fim']} não é um horario válido!");
                $row['quantidade'] = intval($data['quantidade']) > 0 ? $data['quantidade'] : $this->exception("QUANTIDADE {$data['quantidade']} inválido!");
                $row['observacao'] = "";
                $row['aberta'] = !empty($row['data_aprovacao']) ? 1 : 0;
                $row['classificacao'] = "";
                $row['repetir'] = false;
                $row['repetir_semana'] = false;
                $row['procedimentos'] = null;
            }
        } catch (\Exception $e) {
            $row = null;
            $this->exception($e->getMessage());
        }
        return $row;
    }

    protected function getEstabelecimento()
    {
        return !empty($key = array_search($this->data['estabelecimento'], $this->unidades)) ? $key : $this->exception("ESTABELECIMENTO {$this->data['estabelecimento']} não encontrada!");
    }

    protected function getEspecialidade()
    {
        $especialidade =trim($this->data['especialidade']);
        return !empty($key = array_search($especialidade, $this->especialidades)) ? $key : $this->exception("ESPECIALIDADE (GRUPO FPO) {$especialidade} não encontrada!");
    }

    protected function getProfissional()
    {
        return !empty($key = array_search(trim($this->data['profissional']), $this->profissionais)) ? $key : $this->exception("PROFISSIONAL '{$this->data['profissional']}' não encontrada!");
    }

    protected function getEquipamentos()
    {
        return null;
        $data = !empty(trim($this->data['equipamento'])) && !empty(trim($this->data['estabelecimento'])) &&  !empty($this->equipamentos[$this->data['estabelecimento']]) ? $this->equipamentos[$this->data['estabelecimento']] : [];
        return !empty($key = array_search($this->data['equipamento'], $data)) ? $key : $this->exception("EQUIPAMENTO {$this->data['estabelecimento']} > '{$this->data['equipamento']}' não encontrada!");
    }

    protected function getPeriodo()
    {
        return !empty($key = array_search($this->data['periodo'], $this->periodo)) ? $key : $this->exception("PERIODO {$this->data['periodo']} não encontrada!");
    }

    protected function getStatus()
    {
        return !empty($key = array_search($this->data['status'], $this->status)) ? $key : $this->exception("STATUS {$this->data['status']} não encontrada!");
    }

    protected function getNatureza()
    {
        return !empty($key = array_search($this->data['natureza'], $this->natureza)) ? $key : $this->exception("NATUREZA {$this->data['natureza']} não encontrada!");
    }

    protected function getId()
    {
        if (!empty($this->data['id'])) {
            $data = Ofertas::where('codigo', $this->data['id'])->where('data', $this->data['data_atendimento']->format("Y-m-d"))->get();
            if (!empty($data[0])) {
                return $data[0]->id;
            }
        }

        return null;
    }

}