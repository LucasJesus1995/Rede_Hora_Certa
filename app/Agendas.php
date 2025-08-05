<?php

namespace App;

use App\Http\Helpers\Util;
use App\Http\Rules\Agendas\NovoRegistroValidate;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use PDOException;

class Agendas extends Model
{

    protected $table = 'agendas';

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {

            foreach ($model->getAttributes() as $key => $value) {
                $model->$key = Util::String2DB($value);
            }

            if (!empty($model->data) && strstr($model->data, '/')) {
                $model->data = Util::Timestamp2DB($model->data);
            }

            if (empty($model->estabelecimento)) {
                unset($model->estabelecimento);
            }

            if (empty($model->procedimento)) {
                unset($model->procedimento);
            }

            if (empty($model->agenda)) {
                unset($model->agenda);
            }

            if (empty($model->medico)) {
                unset($model->medico);
            }

            if (empty($model->arena_equipamento)) {
                unset($model->arena_equipamento);
            }
        });

    }

    public function linha_cuidados()
    {
        return $this->belongsTo('App\LinhaCuidado', 'linha_cuidado')
            ->select(['linha_cuidado.*']);
    }

    public function arenas()
    {
        return $this->belongsTo('App\Arenas', 'arena')
            ->select(['arenas.*']);
    }

    public static function get($id)
    {
        $key = 'get-agenda-' . $id;

        if (!Cache::has($key)) {
            $data = Agendas::find($id)->toArray();

            if (count($data)) {
                Cache::put($key, $data, CACHE_DAY);
            }
        } else {
            $data = Cache::get($key);
        }

        return $data;
    }

    public static function getRermacacao($ano, $mes, $unidade = null)
    {
        $date = Util::periodoMesPorAnoMes($ano, $mes);

        $sql = Agendas::select(
            [
                'agendas.id',
                'agendas.data',
                'pacientes.nome AS paciente',
                'pacientes.cns',
                'arenas.nome AS arena',
                'linha_cuidado.nome AS linha_cuidado',
                'agenda_remarcada.data AS data_remarcada',
                'agenda_remarcada.id AS id_remarcada',
                'arena_equipamentos.nome AS equipamento',
            ]
        )
            ->join('arenas', 'arenas.id', '=', 'agendas.arena')
            ->join('linha_cuidado', 'linha_cuidado.id', '=', 'agendas.linha_cuidado')
            ->join('pacientes', 'pacientes.id', '=', 'agendas.paciente')
            ->join('agendas AS agenda_remarcada', 'agenda_remarcada.agenda', '=', 'agendas.id')
            ->leftJoin('arena_equipamentos', 'arena_equipamentos.id', '=', 'agendas.arena_equipamento')
            ->where('agendas.motivo_remarcacao', '>', 0)
            ->whereBetween('agendas.data', array($date['start'], $date['end']));

        if (!empty($unidade)) {
            $sql->where('agendas.arena', '=', $unidade);
        }

        $data = $sql->get();

        return !empty($data[0]) ? $data : null;
    }


    public static function nossosNumeros()
    {
        $data = [];
        $key = 'get-nossosNumeros';

        if (!Cache::has($key)) {
            $pacientes = Pacientes::count();

            $exames = Agendas::join('linha_cuidado', 'linha_cuidado.id', '=', 'agendas.linha_cuidado')->where('especialidade', 1)->count();
            $cirurgias = 100000 + Agendas::join('linha_cuidado', 'linha_cuidado.id', '=', 'agendas.linha_cuidado')->where('especialidade', 2)->count();

            $data['atendimentos'] = $exames + $cirurgias;
            $data['pacientes'] = $pacientes;
            $data['exames'] = $exames;
            $data['cirurgias'] = $cirurgias;

            if (count($data)) {
                Cache::put($key, $data, CACHE_MID);
            }
        } else {
            $data = Cache::get($key);
        }

        return $data;
    }

    public static function getAbsenteismo($periodo, $linha_cuidado, $arena)
    {
        $start = $periodo . " 00:00:00";
        $end = $periodo . " 23:59:59";

        $data = self::select(
            [
                'status',
                'id',
            ]
        )
            ->where('data', '>=', $start)
            ->where('data', '<=', $end)
            ->where('arena', $arena)
            ->where('linha_cuidado', $linha_cuidado)
            //->limit(5)
            ->get();

        $_data = [];
        if (count($data)) {
            foreach ($data as $row) {
                switch ($row->status) {
                    case 0 :
                    case 3 :
                    case 5 :
                    case 7 :
                    case 1 :
                        $status = 'falta';
                        break;
                    case 2 :
                    case 4 :
                    case 6 :
                    case 8 :
                    case 10 :
                    case 98 :
                    case 99 :
                        $status = 'atendido';
                        break;
                }

                $_data[$status][] = $row->toArray();

            }
        }

        return $_data;
    }

    public static function getUnidade($agenda)
    {
        $res = Agendas::where('agendas.id', $agenda)
            ->join('arenas', 'arenas.id', '=', 'agendas.arena')
            ->join('unidades_arenas', 'unidades_arenas.arena', '=', 'arenas.id')
            ->select('arenas.id')
            ->limit(1)
            ->get();

        return count($res) ? $res[0]['id'] : null;
    }

    public static function getByPaciente($paciente)
    {
        $data = Agendas::where('paciente', $paciente)
            ->join('arenas', 'arenas.id', '=', 'agendas.arena')
            ->join('linha_cuidado', 'linha_cuidado.id', '=', 'agendas.linha_cuidado')
            ->select(
                [
                    'agendas.id',
                    'agendas.status',
                    'arenas.nome AS arena',
                    'linha_cuidado.nome AS linha_cuidado',
                    'agendas.data',
                    'agendas.id',
                ]
            )
            ->orderBy('id', 'desc')
            ->limit(20)
            ->get();

        return $data;
    }

    public function setStatus($id, $status)
    {

        Agendas::where('id', $id)
            ->update([
                'status' => $status
            ]);

        if ($status == 0) {
            $this->cancelaAtendimento($id);
        }

        return true;
    }


    public function saveData($data)
    {

        try {
            if (isset($data['_token'])) {
                unset($data['_token']);
            }

            $model = empty($data['id']) ? new Agendas() : $this->find($data['id']);

            if (empty($data['data']) && !empty($data['id'])) {
                $date = explode(" ", $model->data);

                $data['data'] = Util::DB2User($date[0]);
            }
            $data['data'] = $data['data'] . ' ' . $data['hora'] . ":00";
            unset($data['hora']);

            if (is_array($data)) {
                foreach ($data as $key => $value) {
                    $model->$key = $value;
                }
            }

            if (empty($model->procedimento)) {
                unset($model->procedimento);
            }

            if (empty($model->agenda)) {
                unset($model->agenda);
            }

            if (empty($model->arena_equipamento)) {
                unset($model->arena_equipamento);
            }

            if (empty($model->medico)) {
                unset($model->medico);
            }

            $removeRegistro = false;
            if (!empty($model->getOriginal()['linha_cuidado'])) {
                $removeRegistro = !($model->getOriginal()['linha_cuidado'] == $data['linha_cuidado']);
            }

            $model->save();

            if ($removeRegistro && !empty($data['id'])) {
                $atendimento = Atendimentos::getByAgenda($data['id']);

                if (!empty($atendimento->id)) {
                    $this->removeRegistro($atendimento->id);
                    Atendimentos::setProcedimentosObrigatorios($atendimento->id, $data['linha_cuidado']);
                }
            }

            $this->updateEstabelecimentoPaciente($data['paciente'], $data['estabelecimento']);

            return true;
        } catch (\Exception $e) {
            exit("<pre>LINE: " . __LINE__ . " - " . print_r($e->getMessage(), 1) . "</pre>"); #debug-edersonsandre
            return false;
        }
    }

    protected function removeRegistro($atendimento)
    {
        Atendimentos::deleteAnamnese($atendimento);
        Atendimentos::deleteProcedimentos($atendimento);
        Atendimentos::deleteMedicamentos($atendimento);
        Atendimentos::deleteLaudoAtendimento($atendimento);
        Atendimentos::deleteAtendimentoTempo($atendimento);
        Atendimentos::deleteAtendimentoStatus($atendimento);
    }

    protected function updateEstabelecimentoPaciente($paciente, $estabelecimento)
    {
        $paciente = Pacientes::find($paciente);
        if (empty($paciente->estabelecimento) || $paciente->estabelecimento == 0) {
            $paciente->estabelecimento = $estabelecimento;
            $paciente->save();
        }
    }

    private function cancelaAtendimento($agenda)
    {
        $atendimento = Atendimentos::getByAgenda($agenda);
        if ($atendimento) {
            $this->removeRegistro($atendimento->id);
            $atendimento->delete();
        }
    }

    public function validaAgenda()
    {
        $validate = new NovoRegistroValidate($this);
        return $validate->process();
    }

    public function validaAgendaData($agenda)
    {
        $agenda = $this->find($agenda);
        if (empty($agenda->id)) {
            throw new \Exception("Agenda não encontrada!");
        }

        $data = current(explode(" ", $agenda->data)) . " 00:00:00";
        $amanha = Carbon::createFromFormat("Y-m-d H:i:s", date('Y-m-d') . " 00:00:00")->addDay(10);

        if (strtotime($data) > strtotime($amanha->toDateTimeString())) {
            throw new \Exception("O periodo da agenda '{$agenda->id}' é superior a data de hoje.<br />Altere a data do agendamento ou aguarde o dia correto!");
        }
    }

    public static function pacientesImportados($data, $unidade, $especialidade)
    {

        $start = $data . " 00:00:00";
        $end = $data . " 23:59:59";

        $sql = Agendas::whereBetween('agendas.data', array($start, $end))
            ->join('arenas', 'arenas.id', '=', 'agendas.arena')
            ->join('linha_cuidado', 'linha_cuidado.id', '=', 'agendas.linha_cuidado')
            ->join('pacientes', 'pacientes.id', '=', 'agendas.paciente')
            ->leftJoin('estabelecimento', 'estabelecimento.id', '=', 'agendas.estabelecimento')
            ->select(
                [
                    'arenas.nome AS estabelecimento',
                    DB::raw('DATE_FORMAT(agendas.data,  "%d/%m/%Y") as data'),
                    DB::raw('DATE_FORMAT(agendas.data,  "%H:%i:%s") as hora'),
                    'pacientes.nome as paciente',
                    DB::raw("IF(pacientes.nascimento IS NULL,  'N/D', TIMESTAMPDIFF(YEAR, pacientes.nascimento, agendas.data)) as idade"),
                    'pacientes.cns as sus',
                    'pacientes.celular as celular',
                    'pacientes.telefone_comercial as telefone_comercial',
                    'pacientes.telefone_residencial as telefone_residencial',
                    'pacientes.telefone_contato as telefone_contato',
                    'linha_cuidado.nome as especialidade',
                    'estabelecimento.nome as ubs',
                ]
            )
            ->orderBy('agendas.data', 'asc');

        if (!empty($unidade)) {
            $sql->where('arenas.id', '=', $unidade);
        }

        if (!empty($especialidade)) {
            $sql->where('linha_cuidado.id', '=', $especialidade);
        }

        $data = $sql->get();

        return $data;
    }

}
