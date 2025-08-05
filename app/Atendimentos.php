<?php

namespace App;

use App\Http\Helpers\Util;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Atendimentos extends Model
{

    protected $table = 'atendimento';

    public static function boot()
    {
        parent::boot();

    }

    public static function ByAgenda($agenda)
    {

        $data = Atendimentos::select(
            [
                'atendimento.id',
                'atendimento.created_at AS chegada',
                'atendimento.tipo_atendimento AS tipo_atendimento',
                'agendas.created_at AS agendamento',
                'agendas.paciente AS paciente',
                'agendas.procedimento AS procedimento',
                'atendimento.preferencial',
                'atendimento.sala',
                'atendimento.status',
                'atendimento.medico',
                'atendimento.agenda',
                'agendas.linha_cuidado AS linha_cuidado'
            ]
        )
            ->join('agendas', 'agendas.id', '=', 'atendimento.agenda')
            ->where('atendimento.agenda', $agenda)
            ->get()
            ->toArray();

        if (!$data) {
            $_agenda = new Agendas();
            $_agenda->validaAgendaData($agenda);
            $_agenda->setStatus($agenda, 2);

            $atendimento = new Atendimentos();
            $atendimento->agenda = $agenda;
            $atendimento->status = 2;
            $atendimento->etapa = 1;

//            $digitadora = Util::getDataDigitadora();
//            if (!empty($digitadora) && !empty($digitadora['doctor'])) {
////                $atendimento->sala = $digitadora['room'];
////                $atendimento->medico = $digitadora['doctor'];
//            }

            $atendimento->save();

            AtendimentoStatus::setStatus($atendimento->id, 2);
            AtendimentoCheckList::checkPreferencial($atendimento->id);

            $data = self::ByAgenda($agenda);
            self::setProcedimentosObrigatorios($data->id, $data->linha_cuidado);
            self::setMedicamentosObrigatorios($data->id, $data->linha_cuidado);
            Atendimentos::UltrassomProcedimentos($data, $atendimento);
            Atendimentos::UpdateEtapa($atendimento->id, 1, true);

            return $data;
        }

        return (object)current($data);
    }

    public static function getNaoFaturados($arena = null, $procedimento = null, $medico = null)
    {
        $sql = Agendas::select(
            [
                'agendas.id',
                'agendas.data',
                'arenas.nome',
                'pacientes.nome AS paciente',
                'linha_cuidado.nome AS linha_cuidado',
                'procedimentos.nome AS procedimento_nome',
                'profissionais.nome AS medico'
            ]
        )
            ->join('atendimento', 'atendimento.agenda', '=', 'agendas.id')
            ->join('arenas', 'arenas.id', '=', 'agendas.arena')
            ->join('linha_cuidado', 'linha_cuidado.id', '=', 'agendas.linha_cuidado')
            ->leftJoin('procedimentos', 'procedimentos.id', '=', 'agendas.procedimento')
            ->join('profissionais', 'profissionais.id', '=', 'atendimento.medico')
            ->join('pacientes', 'pacientes.id', '=', 'agendas.paciente')
            ->where('agendas.status', 10)
            ->where('agendas.data', '>=', '2018-01-01 00:00:00')
            ->where('agendas.data', '<', date('Y-m-01 00:00:00'))
            ->orderBy('arenas.nome', 'asc')
            ->orderBy('agendas.data', 'desc')//->limit(5)
        ;

        if (!empty($arena)) {
            $sql->where('arenas.id', $arena);
        }

        if (!empty($procedimento)) {
            $sql->where('procedimentos.id', $procedimento);
        }

        if (!empty($medico)) {
            $sql->where('profissionais.id', $medico);
        }

        return $sql->get();
    }

    public static function getAtendimentosPacientes($date, $arena = null, $linha_cuidado = null, $status = [6, 8, 10, 98, 99])
    {
        $sql = Agendas::select(
            [
                'agendas.id',
                'agendas.data',
                'agendas.id',
                'agendas.arena_equipamento AS equipamento',
                'agendas.status',
                'pacientes.nome',
                'pacientes.cns',
                'pacientes.cpf',
                'arenas.nome AS arena',
                'linha_cuidado.nome AS linha_cuidado',

            ]
        )
            ->join('pacientes', 'pacientes.id', '=', 'agendas.paciente')
            ->join('arenas', 'arenas.id', '=', 'agendas.arena')
            ->join('linha_cuidado', 'linha_cuidado.id', '=', 'agendas.linha_cuidado')
            ->whereBetween('agendas.data', ["{$date} 00:00:00", "{$date} 23:59:59"])
            ->whereIn('agendas.status', $status)
            ->orderBy('arenas.nome', 'asc')
            ->orderBy('linha_cuidado.nome', 'asc')
            ->orderBy('pacientes.nome', 'asc');

        if (!empty($arena)) {
            $sql->where('arenas.id', '=', $arena);
            $sql->where('agendas.arena', '=', $arena);
        }

        if (!empty($linha_cuidado)) {
            $sql->where('linha_cuidado.id', '=', $linha_cuidado);
            $sql->where('agendas.linha_cuidado', '=', $linha_cuidado);
        }

        return $sql->get();
    }

    public static function CancelaAtendimento($agenda)
    {
        DB::transaction(function () use ($agenda) {

            try {
                $atendimento = Atendimentos::getByAgenda($agenda);

                if (!empty($atendimento->id)) {
                    if (!self::checkFaturamentoAbertoByAtendimento($atendimento->id)) {
                        throw new \Exception("Este atendimento está em faturamento já finalizado não é posssivel cancelar.");
                    }

                    $_atendimento = Atendimentos::find($atendimento->id);

                    $_atendimento->medico = null;
                    $_atendimento->status = 2;
                    $_atendimento->save();

                    Agendas::where('id', $agenda)
                        ->update(
                            ['status' => 2]
                        );

                    AtendimentoProcedimentos::where('atendimento', $atendimento->id)
                        ->update(
                            ['profissional' => null]
                        );

                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();

                throw new \Exception($e->getMessage());
            }
        });

        return true;
    }

    public static function checkFaturamentoAbertoByAtendimento($atendimento)
    {
        $faturamento_procedimentos = FaturamentoProcedimento::getFaturamentoProcedimentoByAtendimento($atendimento);

        if (!empty($faturamento_procedimentos) && !empty($faturamento_procedimentos[0])) {

            $_faturamentos_ids = [];
            foreach ($faturamento_procedimentos as $row) {
                $_faturamentos_ids[$row->faturamento] = $row->faturamento;
            }

            if (!empty($_faturamentos_ids)) {
                foreach ($_faturamentos_ids as $row) {
                    $faturamento = Faturamento::find($row);

                    if (!empty($faturamento) && $faturamento->status == 3) {
                        return false;
                    }

                }
            }
        }

        return true;
    }

    public static function getLinhaCuidado($atendimento)
    {
        $data = self::select("linha_cuidado.*")
            ->join('agendas', 'agendas.id', '=', 'atendimento.agenda')
            ->join('linha_cuidado', 'linha_cuidado.id', '=', 'agendas.linha_cuidado')
            ->where('atendimento.id', $atendimento)
            ->get();

        return !empty($data[0]) ? $data[0] : null;
    }

    public static function getAnexos($atendimento)
    {
        $data = AtendimentoArquivos::select(['id', 'tipo', 'arquivo', 'anotacao'])
            ->where('atendimento', $atendimento)
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->get();

        return !empty($data[0]) ? $data : null;
    }

    public static function getAnexosHistorico($paciente)
    {
        $data = self::select([
            'atendimento_arquivos.id',
            'agendas.id AS agenda',
            'agendas.data AS agenda_data',
            'atendimento_arquivos.tipo',
            'atendimento_arquivos.anotacao',
            'atendimento_arquivos.arquivo',
        ])
            ->join('atendimento_arquivos', 'atendimento.id', '=', 'atendimento_arquivos.atendimento')
            ->join('agendas', 'agendas.id', '=', 'atendimento.agenda')
            ->where('agendas.paciente', $paciente)
            ->where('atendimento_arquivos.status', 1)
            ->orderBy('atendimento_arquivos.id', 'desc')
            ->get();

        return !empty($data[0]) ? $data : null;
    }

    public static function getPacienteByAtendimento($atendimento)
    {
        $data = Pacientes::select(['pacientes.*'])
            ->join('agendas', 'agendas.paciente', '=', 'pacientes.id')
            ->join('atendimento', 'agendas.id', '=', 'atendimento.agenda')
            ->where('atendimento.id', $atendimento)
            ->get();

        return !empty($data[0]) ? $data[0] : null;
    }

    public static function getPacientesFaltas(array $date, $arena = null)
    {
        $sql = Agendas::select(
            [
                'agendas.id',
                'agendas.data',
                'agendas.tipo_atendimento',
                'arenas.nome AS arena',
                'linha_cuidado.nome AS especialidade',
                'pacientes.nome',
                'pacientes.cns',
                'pacientes.celular',
                'pacientes.telefone_comercial',
                'pacientes.telefone_residencial',
                'pacientes.telefone_contato',
                'pacientes.email',
            ]
        )
            ->where('status', 7)
            ->join('pacientes', 'pacientes.id', '=', 'agendas.paciente')
            ->join('arenas', 'arenas.id', '=', 'agendas.arena')
            ->join('linha_cuidado', 'linha_cuidado.id', '=', 'agendas.linha_cuidado')
            ->whereBetween('agendas.data', [$date['start'] . " 00:00:00", $date['end'] . " 23:59:59"])
//            ->limit(10)
        ;

        if (!empty($arena)) {
            $sql->where('arenas.id', $arena);
        }

        $data = $sql->get();

        return !empty($data[0]) ? $data : null;
    }

    public function agendas()
    {
        return $this->belongsTo('App\Agendas', 'agenda')
            ->select(['agendas.*']);
    }

    public function laudos()
    {
        return $this->hasMany('App\AtendimentoLaudo', 'atendimento')->orderBy('id', 'desc');
    }

    public static function get($id)
    {
        $key = 'get-atendimento-' . $id;

        if (!Cache::has($key)) {
            $data = Atendimentos::find($id)->toArray();

            if (count($data)) {
                Cache::put($key, $data, CACHE_DAY);
            }
        } else {
            $data = Cache::get($key);
        }

        return $data;
    }

    private static function UltrassomProcedimentos($data, $atendimento)
    {

        if ($data->linha_cuidado == 7 && !empty($data->procedimento)) {
            $save['quantidade'] = 1;
            $save['procedimento'] = $data->procedimento;
            $save['atendimento'] = $atendimento->id;

            self::saveProcedimento($save, false);
        }

        return null;
    }

    public static function getByAgenda($agenda)
    {
        $atendimento = Atendimentos::where('agenda', $agenda)->get();

        return count($atendimento) ? $atendimento[0] : null;
    }

    public static function saveProcedimento($data, $atualiza_etapa = true)
    {
        $save['quantidade'] = intval($data['quantidade']);
        $save['procedimento'] = $data['procedimento'];
        $save['atendimento'] = $data['atendimento'];
        $save['id'] = self::getByIDAtendimentoProcedimento($save['atendimento'], $save['procedimento']);
        $save['user'] = Util::getUser();

        $procedimento = Procedimentos::find($data['procedimento']);
        $save['multiplicador'] = intval($procedimento['multiplicador']);
        $save['multiplicador_medico'] = intval($procedimento['multiplicador_medico']);

        $profissional = null;
        if (!empty(Auth::user()->profissional)) {
            $profissional = Auth::user()->profissional;
            $save['profissional'] = $profissional;
        }

        if ($save['id'] && !$save['quantidade']) {
            $atendimento = AtendimentoProcedimentos::find($save['id']);
            $atendimento->delete();

            return 2;
        }

        if (Util::getNivel() == 10) {
            $digitadora = Util::getDataDigitadora();

            if (!empty($digitadora['doctor'])) {
                $profissional = $digitadora['doctor'];
                $save['profissional'] = $profissional;
                $save['digitador'] = Util::getUser();
            }
        }

        if (!empty($profissional)) {
            self::_updateProfissionalAtendimento($data['atendimento'], $profissional);
        }

        $atendimento = (empty($save['id'])) ? new AtendimentoProcedimentos() : AtendimentoProcedimentos::find($save['id']);

        foreach ($save as $key => $value) {
            $atendimento->$key = $value;
        }

        if ($atualiza_etapa) {
            Atendimentos::UpdateEtapa($atendimento->atendimento, 3);
        }

        return $atendimento->save();
    }

    public static function saveEvolucao($data)
    {
        $atendimento = Atendimentos::find($data['id']);
        $atendimento->evolucao = $data['evolucao'];
        return $atendimento->save();
    }

    public static function saveAnotacao($data)
    {
        $atendimento = Atendimentos::find($data['id']);
        $atendimento->anotacao = $data['anotacao'];
        return $atendimento->save();
    }

    public static function saveMedicamentos($data)
    {
        $acao = intval($data['acao']);
        if (empty($data['quantidade'])) {
            $acao = false;
        }

        $save['quantidade'] = intval($data['quantidade']);
        $save['medicamento'] = $data['medicamento'];
        $save['atendimento'] = $data['atendimento'];
        $save['id'] = self::getByIDAtendimentoMedicamento($save['atendimento'], $save['medicamento']);
        $save['user'] = Util::getUser();

        if ($save['id'] && !$acao) {
            $atendimento = AtendimentoMedicamento::find($save['id']);
            $atendimento->delete();

            return 2;
        }

        if (empty($save['id'])) {
            $atendimento = new AtendimentoMedicamento();
        } else {
            $atendimento = AtendimentoMedicamento::find($save['id']);
        }

        foreach ($save as $key => $value) {
            $atendimento->$key = $value;
        }

        Atendimentos::UpdateEtapa($atendimento->atendimento, 3);
        return $atendimento->save();
    }

    public static function saveLaudo($data)
    {
        $acao = intval($data['acao']);
        $descricao = preg_replace('/\s+/', " ", $data['laudo_descricao']);
        $descricao = str_replace("<br /> <br /> <br /> <br />", "<br /><br />", $descricao);
        //$descricao = str_replace("<br /> <br /> <br />", "<br /><br />", $descricao);
        $descricao = strip_tags($descricao, '<strong><b><i><br /><br><p><table><tr><td><th>');

        if (!empty($data['laudo'])) {
            $save['laudo'] = $data['laudo'];
        }

        $digitagor = Util::getDataDigitadora();

        $save['resultado'] = $data['resultado'];
        $save['cid'] = !empty($data['cid']) ? $data['cid'] : null;
        $save['biopsia'] = ($data['resultado'] == 3) ? $data['biopsia'] : 0;
        $save['descricao'] = urlencode($descricao);
        $save['atendimento'] = $data['atendimento'];
        $save['id'] = $data['id'];
        $save['user'] = Http\Helpers\Util::getUser();

        if ($save['id'] && !$acao) {
            $atendimento = AtendimentoLaudo::find($save['id']);
            if ($atendimento) {
                $atendimento->delete();
            }

            return 2;
        }

        if (empty($save['id'])) {
            $atendimento = new AtendimentoLaudo();
        } else {
            $atendimento = AtendimentoLaudo::find($save['id']);
        }
        foreach ($save as $key => $value) {
            if (!empty($value)) {
                $atendimento->$key = $value;
            }
        }

        Atendimentos::UpdateEtapa($atendimento->atendimento, 4);
        Atendimentos::UpdateSalaMedico($atendimento->atendimento);

        $save = $atendimento->save();

        return $atendimento;
    }

    public static function saveRecepcao($data)
    {
        $save['tipo'] = intval($data['tipo']);
        $save['value'] = isset($data['value']) ? $data['value'] : false;
        $save['value_descricao'] = $data['value_descricao'];
        $save['anamnense_perguntas'] = $data['id'];
        $save['atendimento'] = $data['atendimento'];
        $save['id'] = self::getByIDAtendimentoAnamnenseRespostas($save['atendimento'], $save['anamnense_perguntas'], $save['tipo']);

        if ($save['value'] || !empty($save['value_descricao'])) {
            if (empty($save['id'])) {
                $atendimento = new AtendimentoAnamnenseRespostas();
            } else {
                $atendimento = AtendimentoAnamnenseRespostas::find($save['id']);
            }

            foreach ($save as $key => $value) {
                $atendimento->$key = $value;
            }

            Atendimentos::UpdateEtapa($atendimento->atendimento, 1);
            if ($save['tipo'] == 2) {
                Atendimentos::UpdateEtapa($atendimento->atendimento, 2);
            }
            if ($save['tipo'] == 4) {
                Atendimentos::UpdateEtapa($atendimento->atendimento, 3);
            }

            return $atendimento->save();
        } else {
            if (!empty($save['id'])) {
                AtendimentoAnamnenseRespostas::find($save['id'])->delete();
                return 2;
            }
        }

        return true;
    }

    public static function getByIDAtendimentoAnamnenseRespostas($atendimento, $anamnense_perguntas, $tipo)
    {
        $data = AtendimentoAnamnenseRespostas::where(array('atendimento' => $atendimento, 'anamnense_perguntas' => $anamnense_perguntas, 'tipo' => $tipo))->get()->first();

        return isset($data->id) ? $data->id : null;
    }

    public static function getByIDAtendimentoProcedimento($atendimento, $procedimento)
    {
        $data = AtendimentoProcedimentos::where(array('atendimento' => $atendimento, 'procedimento' => $procedimento))->get();

        return count($data) ? $data[0]->id : null;
    }

    public static function getByIDAtendimentoMedicamento($atendimento, $medicamento)
    {
        $data = AtendimentoMedicamento::where(array('atendimento' => $atendimento, 'medicamento' => $medicamento))->get()->first();

        return isset($data->id) ? $data->id : null;
    }

    public static function setProcedimentosObrigatorios($atendimento, $linha_cuidado)
    {
        $procedimentos = Util::getProcedimentoObrigatorioByLinhaCuidado($linha_cuidado);

        if ($procedimentos) {
            foreach ($procedimentos as $row) {
                $data = array();
                $data['quantidade'] = 1;
                $data['atendimento'] = $atendimento;
                $data['procedimento'] = $row['id'];
                $data['multiplicador'] = $row['multiplicador'];
                $data['multiplicador_medico'] = $row['multiplicador_medico'];

                self::saveProcedimento($data);
            }
        }
    }

    public static function setMedicamentosObrigatorios($atendimento, $linha_cuidado)
    {
        $medicamentos = LinhaCuidado::getMedicamentosByLinhaCuidado($linha_cuidado);

        if ($medicamentos) {
            foreach ($medicamentos as $row) {
                if ($row['default']) {
                    $_medicamentos = new AtendimentoMedicamento();
                    $_medicamentos->atendimento = $atendimento;
                    $_medicamentos->medicamento = $row['medicamento'];
                    $_medicamentos->quantidade = empty($row['valor']) ? 1 : $row['valor'];
                    $_medicamentos->user = Auth::user()->id;
                    $_medicamentos->save();
                }
            }
        }
    }

    public static function getLaudoByAtendimento($atendimento)
    {
        $atendimento_laudo = AtendimentoLaudo::select(
            [
                'atendimento_laudo.id',
                'atendimento_laudo.laudo',
                'atendimento_laudo.cid',
                'atendimento_laudo.descricao',
                'atendimento_laudo.resultado',
                'atendimento_laudo.biopsia',
                'resultado_biopsia',
                'atendimento_laudo.status_biopsia',
                'cid.codigo AS cid_codigo',
                'cid.descricao AS cid_descricao'
            ]
        )
            ->leftJoin('cid', 'cid.id', '=', 'atendimento_laudo.cid')
            ->where(array('atendimento' => $atendimento))
            ->get();

        return count($atendimento_laudo) ? $atendimento_laudo : null;
    }

    public static function getProcedimentosByAtendimento($atendimento)
    {
        return AtendimentoProcedimentos::where('atendimento', $atendimento)->lists('quantidade', 'procedimento')->toArray();
    }

    public static function getMedicamentosByAtendimento($atendimento)
    {
        return AtendimentoMedicamentos::where('atendimento', $atendimento)->lists('quantidade', 'medicamento')->toArray();
    }

    public static function getByIDAtendimentoLaudo($atendimento)
    {
        $data = AtendimentoLaudo::where(array('atendimento' => $atendimento))->get()->first();

        return isset($data->id) ? $data->id : null;
    }

    public static function deleteAnamnese($atendimento)
    {
        AtendimentoAnamnenseRespostas::where('atendimento', $atendimento)->delete();
    }

    public static function deleteProcedimentos($atendimento)
    {
        AtendimentoProcedimentos::where('atendimento', $atendimento)->delete();
    }

    public static function deleteMedicamentos($atendimento)
    {
        AtendimentoMedicamentos::where('atendimento', $atendimento)->delete();
    }

    public static function deleteLaudo($id)
    {
        AtendimentoLaudo::where('id', $id)->delete();
    }

    public static function deleteLaudoAtendimento($id)
    {
        AtendimentoLaudo::where(array('atendimento' => $id))->delete();
    }

    public static function UpdateEtapa($atendimento, $etapa, $force = false)
    {
        $atendimento = Atendimentos::find($atendimento);

        if ($atendimento) {
            if ($atendimento->etapa < $etapa || $force) {
                $atendimento->etapa = $etapa;
                $atendimento->save();
            }
        }
    }

    public static function UpdateSalaMedico($atendimento)
    {
        $digitadora = Util::getDataDigitadora();

        if (!empty($digitadora) && !empty($digitadora['doctor'])) {
            $atendimento = Atendimentos::find($atendimento);
            $save = false;

//            if (empty($atendimento->medico)) {
            $save = true;
            $atendimento->medico = $digitadora['doctor'];
//            }

            if ($save) {
                $atendimento->save();
            }
        }
    }

    public static function laudoImpressao($atendimento)
    {
        return AtendimentoLaudo::where('atendimento', $atendimento)->orderBy('id', 'desc')->get();
    }

    public static function deleteAtendimentoTempo($atendimento)
    {
        AtendimentoTempo::where(array('atendimento' => $atendimento))->delete();
    }

    public static function deleteAtendimentoStatus($atendimento)
    {
        AtendimentoStatus::where(array('atendimento' => $atendimento))->delete();
    }

    public static function getTempoExecucao($id = null)
    {
        $data = null;
        if ($id) {
            $tempo = AtendimentoTempo::where(array('atendimento' => $id))->get()->toArray();

            return !empty($tempo[0]) ? $tempo[0] : null;
        }

        return $data;
    }

    private static function _updateProfissionalAtendimento($atendimento, $profissional)
    {
        Atendimentos::where('id', $atendimento)
            ->update(['medico' => $profissional]);

        AtendimentoProcedimentos::where('atendimento', $atendimento)
            ->update(['profissional' => $profissional]);

    }

    public static function finalizar($atendimento)
    {
        $_atendimento = Atendimentos::find($atendimento);
        $_atendimento->status = 6;
        $_atendimento->save();

        Agendas::where('id', $_atendimento->agenda)
            ->update(['status' => 6]);

        AtendimentoStatus::setStatus($_atendimento->id, 6);

        $digitador = Util::getDataDigitadora();

        if (!empty($digitador['doctor'])) {
            $medico = $digitador['doctor'];

            self::_updateProfissionalAtendimento($atendimento, $medico);
        }
    }

    public static function finalizarDigitador($atendimento)
    {
        $digitador = Util::getDataDigitadora();
        if (!empty($digitador['doctor'])) {
            $_atendimento = Atendimentos::find($atendimento);
            $_atendimento->status = 10;
            $_atendimento->save();

            Agendas::where('id', $_atendimento->agenda)
                ->update(['status' => 10]);

            AtendimentoStatus::setStatus($_atendimento->id, 10);

            AtendimentoProcedimentos::where('atendimento', $atendimento)
                ->update(['digitador' => Util::getUser()]);

            $medico = $digitador['doctor'];

            self::_updateProfissionalAtendimento($atendimento, $medico);
        }
    }

    public static function setStatus($id, $status)
    {
        $ids = is_array($id) ? $id : [$id];

        Atendimentos::whereIn('id', $ids)
            ->update(['status' => $status]);

        foreach ($ids as $id) {
            AtendimentoStatus::setStatus($id, $status);
        }
    }

    public static function SearchByAtendimento($atendimento, $limit = null)
    {
        $data = array();

        $date = Carbon::now()->addDay('-7')->format('Y-m-d H:i:s');

        $atendimentos = self::select('atendimento.id', 'pacientes.nome')
            ->where('atendimento.id', 'LIKE', "{$atendimento}%")
            ->where('atendimento.created_at', '>', $date)
            ->join('agendas', 'agendas.id', '=', 'atendimento.agenda')
            ->join('pacientes', 'pacientes.id', '=', 'agendas.paciente')
            ->limit($limit)
            ->orderBy('atendimento.id', 'desc')
            ->get();

        if ($atendimentos) {
            foreach ($atendimentos as $row) {
                $data[$row->id] = $row->id . ' - ' . $row['nome'];
            }
        }

        return $data;
    }

}
