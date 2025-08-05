<?php
/**
 * Created by PhpStorm.
 * User: edersonsandre
 * Date: 18/08/15
 * Time: 12:42
 */

namespace App\Http\Helpers;


use App\Agendas;
use App\Arenas;
use App\AtendimentoProcedimentos;
use App\Atendimentos;
use App\LinhaCuidado;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;

class Relatorios
{

    /**
     * @param $linha_cuidado
     *
     * Medicos da linha de cuidados
     *
     */
    public static function BI_Procedimentos($linha_cuidado, $mes, $ano)
    {
        $medicos = LinhaCuidado::getMedicosByLinhaCuidado($linha_cuidado);

    }

    public static function getTotalProcedimentoDia($arena, $linha_cuidado, $procedimento, $medico, $mes, $ano, $dia)
    {
        $data['inicial'] = "{$ano}-{$mes}-{$dia} 00:00:00";
        $data['final'] = "{$ano}-{$mes}-{$dia} 23:59:59";

        return self::_getTotalProcedimentosBPA($arena, $linha_cuidado, $procedimento, $medico, $data);
    }


    public static function _getTotalProcedimentosBPA($arena, $linha_cuidado = null, $procedimento, $medico = null, $data, $finalizacao = null)
    {
        $_data = array();

        $_status = [98, 99, 6, 8, 10];

        $res = Agendas::select(
            DB::raw('DATE_FORMAT(agendas.data, "%d") AS dia'),
            DB::raw('SUM(atendimento_procedimentos.quantidade) AS total')
        )
            ->where('agendas.data', '>=', $data['inicial'])
            ->where('agendas.data', '<=', $data['final'])
            ->whereIn('atendimento.status', $_status)
            ->whereIn('procedimentos.contador', [1])
            ->join('atendimento', 'atendimento.agenda', '=', 'agendas.id')
            ->join('atendimento_procedimentos', 'atendimento_procedimentos.atendimento', '=', 'atendimento.id')
            ->join('procedimentos', 'atendimento_procedimentos.procedimento', '=', 'procedimentos.id')
            ->groupBy('dia');

        if (!empty($finalizacao) && in_array($finalizacao, [6, 8, 10])) {
            $res->join('atendimento_status', function ($join) use ($finalizacao) {
                $join->on('atendimento_status.atendimento', '=', 'atendimento.id')
                    ->whereIn('atendimento_status.status', [$finalizacao]);
            });
        }

        if (!empty($arena)) {
            $res->where('agendas.arena', $arena);
        }

        if (!empty($linha_cuidado)) {
            $res->where('agendas.linha_cuidado', $linha_cuidado);
        }

        if (!empty($procedimento)) {
            $res->where('atendimento_procedimentos.procedimento', $procedimento);
        }

        if (!empty($medico)) {
            $res->where('atendimento_procedimentos.profissional', $medico);
        }

        $result = $res->get()->toArray();
        if (!empty($result)) {
            foreach ($result as $row) {
                $_data[$row['dia']] = $row['total'];
            }
        }

        return $_data;
    }

    public static function getTotalProcedimentoMes($arena, $linha_cuidado = null, $procedimento, $medico, $mes, $ano, $finalizacao = null)
    {
        $ultimo_dia = date("t", mktime(0, 0, 0, $mes, '01', $ano));

        $data['inicial'] = "{$ano}-{$mes}-01 00:00:00";
        $data['final'] = "{$ano}-{$mes}-{$ultimo_dia} 23:59:59";

        return self::_getTotalProcedimentosBPA($arena, $linha_cuidado, $procedimento, $medico, $data, $finalizacao);
    }

    /*
     * @todo COLOCAR A DATA DE FECHAMENTO
     * @todo COLOCAR SOMENTE PARA PEGAR ATENDIMENTO FINALIZADOS
     */
    public static function _getTotalProcedimentos($arena, $linha_cuidado, $procedimento, $medico = null, $data)
    {

        $res = Agendas::select(
            'pacientes.id as prontuario',
            DB::raw('SUM(atendimento_procedimentos.quantidade) AS total'),
            DB::raw('IF(pacientes.sexo = "1", COUNT(sexo),null) AS masculino'),
            DB::raw('IF(pacientes.sexo = "2", COUNT(sexo),null) AS feminino'),
            DB::raw('IF(FLOOR(DATEDIFF(NOW(), pacientes.nascimento) / 365) <= 9, COUNT(1),null) AS zero_nove'),
            DB::raw('IF(FLOOR(DATEDIFF(NOW(), pacientes.nascimento) / 365) > 9 AND FLOOR(DATEDIFF(NOW(), pacientes.nascimento) / 365) < 20, COUNT(1),null) AS dez_dezenove'),
            DB::raw('IF(FLOOR(DATEDIFF(NOW(), pacientes.nascimento) / 365) > 19 AND FLOOR(DATEDIFF(NOW(), pacientes.nascimento) / 365) < 30, COUNT(1),null) AS vintenove_trinta'),
            DB::raw('IF(FLOOR(DATEDIFF(NOW(), pacientes.nascimento) / 365) > 29 AND FLOOR(DATEDIFF(NOW(), pacientes.nascimento) / 365) < 40, COUNT(1),null) AS trinta_quarenta'),
            DB::raw('IF(FLOOR(DATEDIFF(NOW(), pacientes.nascimento) / 365) > 39 AND FLOOR(DATEDIFF(NOW(), pacientes.nascimento) / 365) < 50, COUNT(1),null) AS quarenta_cinquenta'),
            DB::raw('IF(FLOOR(DATEDIFF(NOW(), pacientes.nascimento) / 365) > 49 AND FLOOR(DATEDIFF(NOW(), pacientes.nascimento) / 365) < 65, COUNT(1),null) AS cinquenta_sessenta'),
            DB::raw('IF(FLOOR(DATEDIFF(NOW(), pacientes.nascimento) / 365) > 64, COUNT(1),null) AS acima_sessenta')
        )
            ->where('agendas.data', '>=', $data['inicial'])
            ->where('agendas.data', '<=', $data['final'])
            ->join('atendimento', 'atendimento.agenda', '=', 'agendas.id')
            ->join('atendimento_procedimentos', 'atendimento_procedimentos.atendimento', '=', 'atendimento.id')
            ->join('procedimentos', 'procedimentos.id', '=', 'atendimento_procedimentos.procedimento')
            ->join('pacientes', 'pacientes.id', '=', 'agendas.paciente');

        if (!empty($arena)) {
            $res->where('agendas.arena', $arena);
        }

        if (!empty($linha_cuidado)) {
            $res->where('agendas.linha_cuidado', $linha_cuidado);
        }

        if (!empty($procedimento)) {
            $res->where('procedimentos.id', $procedimento);
        }

        $res->groupBy('pacientes.id');
        $result = $res->get()->toArray();

        return !empty($result[0]['total']) ? $result[0]['total'] : null;
    }

    public static function RelatorioCondutaDataProfissionais($data, $arena = null, $linha_cuidado = null, $medico = null)
    {
        $data_inicial = Util::Date2DB($data['inicial']);
        $data_final = Util::Date2DB($data['final']);

        $where_medico = ($medico) ? "AND profissionais.id = {$medico}" : null;
        $where_arena = ($arena) ? "AND agendas.arena = {$arena}" : null;
        $where_linha_cuidado = ($linha_cuidado) ? "AND agendas.linha_cuidado = {$linha_cuidado}" : null;
        $where_status = (Util::getNivel() == 10) ? "AND atendimento.status IN (6, 8, 10, 98, 99)" : "AND atendimento.status IN (6, 8, 98, 99)";

        $results = DB::select("SELECT DISTINCT
                                    tipo_atendimento.nome AS tipo_atendimento,
                                    atendimento_auxiliar.conduta AS conduta_principal,
                                    atendimento_auxiliar.conduta_secundaria,
                                    pacientes.id AS prontuario,
                                    pacientes.nome,
                                    pacientes.cns,
                                    pacientes.sexo,
                                    pacientes.nascimento,
                                    COUNT(atendimento_auxiliar.id) AS total
                                FROM atendimento_auxiliar
                                    join atendimento on atendimento_auxiliar.atendimento = atendimento.id
                                    join tipo_atendimento on tipo_atendimento.id = atendimento.tipo_atendimento
                                    join agendas on atendimento.agenda = agendas.id
                                    join profissionais on atendimento.medico = profissionais.id
                                    join pacientes on agendas.paciente = pacientes.id
                                WHERE agendas.data BETWEEN '{$data_inicial} 00:00:00'  AND '{$data_final} 23:59:59'
                                    AND atendimento_auxiliar.conduta IS NOT NULL
                                    {$where_status}
                                    {$where_linha_cuidado}
                                    {$where_arena}
                                    {$where_medico}
                                GROUP BY
                                    tipo_atendimento.nome,
                                    atendimento_auxiliar.conduta,
                                    atendimento_auxiliar.conduta_secundaria,
                                    pacientes.id
                                ORDER BY tipo_atendimento.nome, atendimento_auxiliar.conduta, pacientes.nome ASC
                                    ");

        return count($results) ? $results : false;
    }

    public static function RelatorioCondutaProfissionais($data, $arena = null, $linha_cuidado = null, $medico = null)
    {
        $data_inicial = Util::Date2DB($data['inicial']);
        $data_final = Util::Date2DB($data['final']);

        $where_medico = ($medico) ? "AND profissionais.id = {$medico}" : null;
        $where_arena = ($arena) ? "AND agendas.arena = {$arena}" : null;
        $where_linha_cuidado = ($linha_cuidado) ? "AND agendas.linha_cuidado = {$linha_cuidado}" : null;
        $where_status = (Util::getNivel() == 10) ? "AND atendimento.status IN (6, 8, 10, 98, 99)" : "AND atendimento.status IN (6, 8, 98, 99)";

        $results = DB::select("SELECT DISTINCT
                                    profissionais.id,
                                    profissionais.nome,
                                    profissionais.cro,
                                    COUNT(atendimento_auxiliar.id) AS total
                                FROM atendimento_auxiliar
                                    join atendimento on atendimento_auxiliar.atendimento = atendimento.id
                                    join agendas on atendimento.agenda = agendas.id
                                    join profissionais on atendimento.medico = profissionais.id
                                    join pacientes on agendas.paciente = pacientes.id
                                WHERE agendas.data BETWEEN '{$data_inicial} 00:00:00'  AND '{$data_final} 23:59:59'
                                    AND atendimento_auxiliar.conduta IS NOT NULL
                                    {$where_status}
                                    {$where_linha_cuidado}
                                    {$where_arena}
                                    {$where_medico}
                                GROUP BY
                                    profissionais.id,
                                    profissionais.nome,
                                    profissionais.cro
                                    ");

        return count($results) ? $results : false;
    }

    public static function RelatorioProducaoRelacaoProfissionais($data, $arena = null, $linha_cuidado = null, $medico = null, $digitador = null)
    {
        $data_inicial = Util::Date2DB($data['inicial']);
        $data_final = Util::Date2DB($data['final']);

        $where_medico = ($medico) ? "AND profissionais.id = {$medico}" : null;
        $where_arena = ($arena) ? "AND agendas.arena = {$arena}" : null;
        $where_linha_cuidado = ($linha_cuidado) ? "AND agendas.linha_cuidado = {$linha_cuidado}" : null;
        $where_status = (Util::getNivel() == 10) ? "AND atendimento.status IN (6, 8, 10, 98, 99)" : "AND atendimento.status IN (6, 8, 98, 99)";
        $where_digitador = (Util::getNivel() == 10) ? "AND atendimento_procedimentos.digitador = " . Util::getUser() : null;
        $where_digitador_2 = ($digitador) ? "AND atendimento_procedimentos.digitador = " . $digitador : null;

        $results = DB::select("SELECT DISTINCT
                                    profissionais.id,
                                    profissionais.nome,
                                    profissionais.cro,
                                    sum(atendimento_procedimentos.quantidade) AS total
                                FROM atendimento_procedimentos
                                    join atendimento on atendimento_procedimentos.atendimento = atendimento.id
                                    join procedimentos on atendimento_procedimentos.procedimento = procedimentos.id
                                    join agendas on atendimento.agenda = agendas.id
                                    join profissionais on atendimento_procedimentos.profissional = profissionais.id
                                    join pacientes on agendas.paciente = pacientes.id
                                WHERE agendas.data BETWEEN '{$data_inicial} 00:00:00'  AND '{$data_final} 23:59:59'
                                    AND procedimentos.contador in (1)
                                    {$where_status}
                                    {$where_linha_cuidado}
                                    {$where_arena}
                                    {$where_medico}
                                    {$where_digitador}
                                    {$where_digitador_2}
                                GROUP BY
                                    profissionais.id,
                                    profissionais.nome,
                                    profissionais.cro
                                    ");

        return count($results) ? $results : false;
    }

    public static function RelatorioProducao($data, $arena = null, $linha_cuidado = null, $medico = null, $digitador = null)
    {
        $data = json_decode($data);

        $data_inicial = Util::Date2DB($data->inicial);
        $data_final = Util::Date2DB($data->final);

        $where_medico = ($medico) ? "AND profissionais.id = {$medico}" : null;
        $where_arena = ($arena) ? "AND agendas.arena = {$arena}" : null;
        $where_linha_cuidado = ($linha_cuidado) ? "AND agendas.linha_cuidado = {$linha_cuidado}" : null;
        $where_status = (Util::getNivel() == 10) ? "AND atendimento.status IN (6, 8, 10, 98, 99)" : "AND atendimento.status IN (6, 8, 98, 99)";
        $where_digitador = (Util::getNivel() == 10) ? "AND atendimento_procedimentos.digitador = " . Util::getUser() : null;
        $where_digitador_2 = ($digitador) ? "AND atendimento_procedimentos.digitador = " . $digitador : null;

        $results = DB::select("SELECT 
                                    procedimentos.id,
                                    procedimentos.nome,
                                    count(1) as total
                                    
                                FROM atendimento_procedimentos
                                join atendimento on atendimento_procedimentos.atendimento = atendimento.id
                                join agendas on atendimento.agenda = agendas.id     
                                join pacientes on pacientes.id = agendas.paciente
                                join profissionais on atendimento_procedimentos.profissional = profissionais.id
                                join procedimentos on atendimento_procedimentos.procedimento = procedimentos.id

                                WHERE agendas.data BETWEEN '{$data_inicial} 00:00:00'  AND '{$data_final} 23:59:59'
                                    AND procedimentos.contador in (1)
                                    {$where_linha_cuidado}
                                    {$where_arena}
                                    {$where_medico}
                                    {$where_status}
                                    {$where_digitador}
                                    {$where_digitador_2}
                                GROUP BY 
                                    procedimentos.id,
                                    procedimentos.nome
                                ORDER BY procedimentos.ordem ASC
                                ");


        return count($results) ? $results : false;
    }

    public static function RelatorioProducaoProfissional($arena = null, $linha_cuidado = null, $medico = null, $data = null, $procedimento = null, $digitador = null)
    {
        $data = json_decode($data);

        $data_inicial = Util::Date2DB($data->inicial);
        $data_final = Util::Date2DB($data->final);

        $where_medico = ($medico) ? "AND profissionais.id = {$medico}" : null;
        $where_arena = ($arena) ? "AND agendas.arena = {$arena}" : null;
        $where_linha_cuidado = ($linha_cuidado) ? "AND agendas.linha_cuidado = {$linha_cuidado}" : null;
        $where_procedimentos = ($procedimento) ? "AND procedimentos.id = {$procedimento}" : null;
        $where_status = (Util::getNivel() == 10) ? "AND atendimento.status IN (6, 8, 10, 98, 99)" : "AND atendimento.status IN (6, 8,98, 99)";
        $where_digitador = (Util::getNivel() == 10) ? "AND atendimento_procedimentos.digitador = " . Util::getUser() : null;
        $where_digitador_2 = ($digitador) ? "AND atendimento_procedimentos.digitador = " . $digitador : null;

        $results = DB::select("SELECT DISTINCT
                                    SUM(atendimento_procedimentos.quantidade) AS total,
                                    procedimentos.id,
                                    procedimentos.contador as contador_procedimento,
                                    procedimentos.nome as nome_procedimento
                                from atendimento_procedimentos
                                    join atendimento on atendimento_procedimentos.atendimento = atendimento.id
                                    join procedimentos on atendimento_procedimentos.procedimento = procedimentos.id
                                    join agendas on atendimento.agenda = agendas.id
                                    join profissionais on atendimento_procedimentos.profissional = profissionais.id
                                    join pacientes on agendas.paciente = pacientes.id
                                WHERE agendas.data BETWEEN '{$data_inicial} 00:00:00'  AND '{$data_final} 23:59:59'
                                    {$where_arena}
                                    {$where_linha_cuidado}
                                    {$where_medico}
                                    {$where_procedimentos}
                                    {$where_status}
                                    {$where_digitador}
                                    {$where_digitador_2}
                                    AND procedimentos.contador in (1)
                                    
                                    
                                ");

        return !empty($results[0]) ? $results[0] : false;
    }

    public static function RelatorioProducaoDetalhamentoPaciente2($data = null, $arena = null, $linha_cuidado = null, $medico = null, $procedimento = null, $digitador = null)
    {
        $data = json_decode($data);

        $data_inicial = Util::Date2DB($data->inicial);
        $data_final = Util::Date2DB($data->final);

        $where_medico = ($medico) ? "AND profissionais.id = {$medico}" : null;
        $where_arena = ($arena) ? "AND agendas.arena = {$arena}" : null;
        $where_linha_cuidado = ($linha_cuidado) ? "AND agendas.linha_cuidado = {$linha_cuidado}" : null;
        $where_procedimentos = ($procedimento) ? "AND procedimentos.id = {$procedimento}" : null;
        $where_status = (Util::getNivel() == 10) ? "AND atendimento.status IN (6, 8, 10, 98, 99)" : "AND atendimento.status IN (6, 8,98, 99)";
        $where_digitador = (Util::getNivel() == 10) ? "AND atendimento_procedimentos.digitador = " . Util::getUser() : null;
        $where_digitador2 = ($digitador) ? "AND atendimento_procedimentos.digitador = " . $digitador : null;

        $results = DB::select("SELECT
                                    pacientes.*
                                from atendimento_procedimentos
                                    join atendimento on atendimento_procedimentos.atendimento = atendimento.id
                                    join procedimentos on atendimento_procedimentos.procedimento = procedimentos.id
                                    join agendas on atendimento.agenda = agendas.id
                                    join profissionais on atendimento_procedimentos.profissional = profissionais.id
                                    #left join atendimento_laudo on atendimento_procedimentos.atendimento = atendimento_laudo.atendimento
                                    join pacientes on agendas.paciente = pacientes.id
                                WHERE  agendas.data BETWEEN '{$data_inicial} 00:00:00'  AND '{$data_final} 23:59:59'
                                    {$where_linha_cuidado}
                                    {$where_medico}
                                    {$where_arena}
                                    {$where_procedimentos}
                                    {$where_status}
                                    {$where_digitador}
                                    {$where_digitador2}
                                    AND procedimentos.contador in (1)
                                ");

        return !empty($results[0]) ? $results : false;
    }


    public static function QuantidadeDeAgendamentosSemLaudo($data = null, $arena = null, $linha_cuidado = null, $medico = null)
    {
        $data = json_decode($data);

        $data_inicial = Util::Date2DB($data->inicial);
        $data_final = Util::Date2DB($data->final);

        $where_medico = ($medico) ? "AND profissionais.id = {$medico}" : null;
        $where_arena = ($arena) ? "AND agendas.arena = {$arena}" : null;
        $where_linha_cuidado = ($linha_cuidado) ? "AND agendas.linha_cuidado = {$linha_cuidado}" : null;

        $results = DB::select("SELECT  
                                    count(distinct atendimento.id) as total_quantidade 
                                FROM atendimento_procedimentos
                                    join atendimento on atendimento_procedimentos.atendimento = atendimento.id
                                    join agendas on atendimento.agenda = agendas.id
                                    join profissionais on atendimento_procedimentos.profissional = profissionais.id
                                where agendas.data between '{$data_inicial} 00:00:00' and '{$data_final} 23:59:59'
                                    {$where_arena}
                                    {$where_linha_cuidado}
                                    {$where_medico}
                                    AND agendas.status = 5
                            ");

        return !empty($results[0]) ? $results[0]->total_quantidade : false;
    }

    public static function RelatorioProducaoDetalhamentoPaciente($data = null, $arena = null, $linha_cuidado = null, $medico = null, $digitador = null)
    {
        $data = json_decode($data);

        $data_inicial = Util::Date2DB($data->inicial);
        $data_final = Util::Date2DB($data->final);

        $where_medico = ($medico) ? "AND profissionais.id = {$medico}" : null;
        $where_arena = ($arena) ? "AND agendas.arena = {$arena}" : null;
        $where_linha_cuidado = ($linha_cuidado) ? "AND agendas.linha_cuidado = {$linha_cuidado}" : null;
        $where_status = (Util::getNivel() == 10) ? "AND atendimento.status IN (6, 8, 10, 98, 99)" : "AND atendimento.status IN (6, 8, 98, 99)";
        $where_digitador = (Util::getNivel() == 10) ? "AND atendimento_procedimentos.digitador = " . Util::getUser() : null;
        $where_digitador2 = ($digitador) ? "AND atendimento_procedimentos.digitador = " . $digitador : null;

        $results = DB::select("SELECT
                                    pacientes.id AS prontuario,
                                    pacientes.nome AS paciente_nome,
                                    pacientes.cns AS paciente_cns,
                                    pacientes.nascimento AS paciente_nascimento,
                                    pacientes.sexo AS paciente_sexo,
                                    atendimento.id AS atendimento_id,
                                    procedimentos.nome AS procedimento_nome,
                                    users.name AS digitador
                                FROM atendimento_procedimentos
                                    join atendimento on atendimento_procedimentos.atendimento = atendimento.id
                                    join agendas on atendimento.agenda = agendas.id
                                    join pacientes on agendas.paciente = pacientes.id
                                    join profissionais on atendimento_procedimentos.profissional = profissionais.id
                                    join procedimentos on atendimento_procedimentos.procedimento = procedimentos.id
                                    left join users on users.id = atendimento_procedimentos.digitador
                                WHERE agendas.data BETWEEN '{$data_inicial} 00:00:00'  AND '{$data_final} 23:59:59'
                                    $where_arena
                                    $where_linha_cuidado
                                    $where_medico
                                    AND procedimentos.contador in (1)
                                    {$where_status}
                                    {$where_digitador}
                                    {$where_digitador2}

                                group BY  pacientes.id, pacientes.cns
                                order by  pacientes.nome
                                ");

        return !empty($results[0]) ? $results : false;
    }

    public static function RelatorioProducaoDetalhamentoPaciente3($data = null, $arena = null, $linha_cuidado = null, $medico = null, $digitador = null)
    {
        $data = json_decode($data);

        $data_inicial = Util::Date2DB($data->inicial);
        $data_final = Util::Date2DB($data->final);

        $where_medico = ($medico) ? "AND profissionais.id = {$medico}" : null;
        $where_arena = ($arena) ? "AND agendas.arena = {$arena}" : null;
        $where_linha_cuidado = ($linha_cuidado) ? "AND agendas.linha_cuidado = {$linha_cuidado}" : null;
        $where_status = (Util::getNivel() == 10) ? "AND atendimento.status IN (6, 8, 10, 98, 99)" : "AND atendimento.status IN (6, 8, 98, 99)";
        $where_digitador = (Util::getNivel() == 10) ? "AND atendimento_procedimentos.digitador = " . Util::getUser() : null;
        $where_digitador2 = ($digitador) ? "AND atendimento_procedimentos.digitador = " . $digitador : null;

        $results = DB::select("SELECT
                                    pacientes.id AS prontuario,
                                    pacientes.cns AS paciente_cns,
                                    pacientes.nome AS paciente_nome,
                                    FLOOR(DATEDIFF(agendas.data, pacientes.nascimento) / 365.25) AS idade,
                                    IF(pacientes.sexo = 1, 'Masculino', 'Feminino') as sexo,
                                    atendimento.id AS atendimento_id,
                                    procedimentos.nome AS procedimento,
                                    UPPER(users.name) AS digitador
                                FROM atendimento_procedimentos
                                    join atendimento on atendimento_procedimentos.atendimento = atendimento.id
                                    join agendas on atendimento.agenda = agendas.id
                                    join pacientes on agendas.paciente = pacientes.id
                                    join profissionais on atendimento_procedimentos.profissional = profissionais.id
                                    join procedimentos on atendimento_procedimentos.procedimento = procedimentos.id
                                    left join users on users.id = atendimento_procedimentos.digitador
                                WHERE agendas.data BETWEEN '{$data_inicial} 00:00:00'  AND '{$data_final} 23:59:59'
                                    $where_arena
                                    $where_linha_cuidado
                                    $where_medico
                                    AND procedimentos.contador in (1)
                                    {$where_status}
                                    {$where_digitador}
                                    {$where_digitador2}
                                order by  pacientes.id asc,   procedimentos.nome 
                                ");

        return !empty($results[0]) ? $results : false;
    }


    public static function RelatorioAgendadoPorDia($arena, $data = null)
    {

        $results = DB::select("SELECT count(1) AS total FROM agendas  WHERE agendas.arena = {$arena} AND agendas.data BETWEEN '{$data} 00:00:00'  AND '{$data} 23:59:59'");

        return !empty($results[0]) ? $results[0]->total : false;
    }

    public static function aderenciaDigitador($params)
    {
        $data['geral'] = [];
        $data['detalhado'] = [];
        $status_agenda = [6, 8, 10, 98, 99];

        $data_inicial = Util::Date2DB($params['data_inicial']) . " 00:00:00";
        $data_final = Util::Date2DB($params['data_final']) . " 23:59:59";

        $sql = Agendas::select(
            [
                DB::raw('DATE_FORMAT(agendas.data,"%Y-%m-%d" ) AS data_agenda'),
                DB::raw('COUNT(1) AS total')
            ]
        )
            ->whereBetween('agendas.data', [$data_inicial, $data_final])
            ->groupBy('data_agenda');

        if (!empty($params['arena']))
            $sql->where('agendas.arena', $params['arena']);

        $digitador_agendados = $sql->get();

        $sql = Agendas::select(
            [
                DB::raw('DATE_FORMAT(agendas.data,"%Y-%m-%d" ) AS data_agenda'),
                DB::raw('COUNT(1) AS total')
            ]
        )
            ->join('atendimento', 'agendas.id', '=', 'atendimento.agenda')
            ->whereBetween('agendas.data', [$data_inicial, $data_final])
            ->whereIn('agendas.status', $status_agenda)
            ->groupBy('data_agenda');

        if (!empty($params['arena']))
            $sql->where('agendas.arena', $params['arena']);

        $digitador_atendimento = $sql->lists('total', 'data_agenda')->toArray();

        $sql = Agendas::select(
            [
                DB::raw('DATE_FORMAT(agendas.data,"%Y-%m-%d" ) AS data_agenda'),
                DB::raw('COUNT(DISTINCT atendimento_procedimentos.atendimento) AS total')
            ]
        )
            ->join('atendimento', 'agendas.id', '=', 'atendimento.agenda')
            ->join('atendimento_procedimentos', 'atendimento_procedimentos.atendimento', '=', 'atendimento.id')
            ->whereNotNull('atendimento_procedimentos.digitador')
            ->whereBetween('agendas.data', [$data_inicial, $data_final])
            ->whereIn('agendas.status', $status_agenda)
            ->groupBy('data_agenda');

        if (!empty($params['arena']))
            $sql->where('agendas.arena', $params['arena']);

        $digitador = $sql->lists('total', 'data_agenda')->toArray();

        $sql = Agendas::select(
            [
                DB::raw('DATE_FORMAT(agendas.data,"%Y-%m-%d" ) AS data_agenda'),
                DB::raw('COUNT(DISTINCT atendimento_procedimentos.atendimento) AS total')
            ]
        )
            ->join('atendimento', 'agendas.id', '=', 'atendimento.agenda')
            ->join('atendimento_procedimentos', 'atendimento_procedimentos.atendimento', '=', 'atendimento.id')
            ->whereNotNull('atendimento_procedimentos.faturista')
            ->whereBetween('agendas.data', [$data_inicial, $data_final])
            ->whereIn('agendas.status', $status_agenda)
            ->groupBy('data_agenda');

        if (!empty($params['arena']))
            $sql->where('agendas.arena', $params['arena']);

        $faturista = $sql->lists('total', 'data_agenda')->toArray();

        $sql = Agendas::select(
            [
                DB::raw('DATE_FORMAT(agendas.data,"%Y-%m-%d" ) AS data_agenda'),
                DB::raw('COUNT(DISTINCT atendimento.id) AS total')
            ]
        )
            ->join('arenas', 'arenas.id', '=', 'agendas.arena')
            ->join('atendimento', 'agendas.id', '=', 'atendimento.agenda')
            ->join('atendimento_tempo', 'atendimento_tempo.atendimento', '=', 'atendimento.id')
            ->whereNotNull('atendimento_tempo.recepcao_in')
            ->whereBetween('agendas.data', [$data_inicial, $data_final])
            ->whereIn('agendas.status', $status_agenda)
            ->groupBy('data_agenda');

        if (!empty($params['arena']))
            $sql->where('agendas.arena', $params['arena']);

        $arenas_recepcao = $sql->lists('total', 'data_agenda')->toArray();

        foreach ($digitador_agendados as $row) {
            $key = $row->data_agenda;
            $new_key = Util::DB2UserDiaMes($key);

            $data['geral'][$new_key]['Agendados'] = $row->total;
            $data['geral'][$new_key]['Atendidos'] = array_key_exists($key, $digitador_atendimento) ? $digitador_atendimento[$key] : 0;
            $data['geral'][$new_key]['Digitador'] = array_key_exists($key, $digitador) ? $digitador[$key] : 0;
            $data['geral'][$new_key]['Faturista'] = array_key_exists($key, $faturista) ? $faturista[$key] : 0;
            $data['geral'][$new_key]['Recepcao'] = array_key_exists($key, $arenas_recepcao) ? $arenas_recepcao[$key] : 0;
        }

        //// DETALHADO
        $sql = Agendas::select(
            [
                DB::raw('arenas.nome AS arena_nome'),
                DB::raw('COUNT(agendas.id) AS total')
            ]
        )
            ->join('arenas', 'arenas.id', '=', 'agendas.arena')
            ->whereBetween('agendas.data', [$data_inicial, $data_final])
            ->groupBy('arena_nome')
            ->orderBy('arena_nome', 'asc');

        if (!empty($params['arena']))
            $sql->where('agendas.arena', $params['arena']);

        $arenas_agendamento = $sql->get()->toArray();

        $sql = Agendas::select(
            [
                DB::raw('arenas.nome AS arena_nome'),
                DB::raw('COUNT(atendimento.id) AS total')
            ]
        )
            ->join('arenas', 'arenas.id', '=', 'agendas.arena')
            ->join('atendimento', 'agendas.id', '=', 'atendimento.agenda')
            ->whereBetween('agendas.data', [$data_inicial, $data_final])
            ->whereIn('agendas.status', $status_agenda)
            ->groupBy('arena_nome')
            ->orderBy('arena_nome', 'asc');

        if (!empty($params['arena']))
            $sql->where('agendas.arena', $params['arena']);

        $arenas_atendimento = $sql->lists('total', 'arena_nome')->toArray();

        $sql = Agendas::select(
            [
                DB::raw('arenas.nome AS arena_nome'),
                DB::raw('COUNT(DISTINCT atendimento_procedimentos.atendimento) AS total')
            ]
        )
            ->join('arenas', 'arenas.id', '=', 'agendas.arena')
            ->join('atendimento', 'agendas.id', '=', 'atendimento.agenda')
            ->join('atendimento_procedimentos', 'atendimento_procedimentos.atendimento', '=', 'atendimento.id')
            ->whereNotNull('atendimento_procedimentos.digitador')
            ->whereBetween('agendas.data', [$data_inicial, $data_final])
            ->whereIn('agendas.status', $status_agenda)
            ->groupBy('arena_nome');

        if (!empty($params['arena']))
            $sql->where('agendas.arena', $params['arena']);

        $arenas_digitador = $sql->lists('total', 'arena_nome')->toArray();

        $sql = Agendas::select(
            [
                DB::raw('arenas.nome AS arena_nome'),
                DB::raw('COUNT(DISTINCT atendimento_procedimentos.atendimento) AS total')
            ]
        )
            ->join('arenas', 'arenas.id', '=', 'agendas.arena')
            ->join('atendimento', 'agendas.id', '=', 'atendimento.agenda')
            ->join('atendimento_procedimentos', 'atendimento_procedimentos.atendimento', '=', 'atendimento.id')
            ->whereNotNull('atendimento_procedimentos.faturista')
            ->whereBetween('agendas.data', [$data_inicial, $data_final])
            ->whereIn('agendas.status', $status_agenda)
            ->groupBy('arena_nome');

        if (!empty($params['arena']))
            $sql->where('agendas.arena', $params['arena']);

        $arenas_faturista = $sql->lists('total', 'arena_nome')->toArray();

        $sql = Agendas::select(
            [
                DB::raw('arenas.nome AS arena_nome'),
                DB::raw('COUNT(DISTINCT atendimento.id) AS total')
            ]
        )
            ->join('arenas', 'arenas.id', '=', 'agendas.arena')
            ->join('atendimento', 'agendas.id', '=', 'atendimento.agenda')
            ->join('atendimento_tempo', 'atendimento_tempo.atendimento', '=', 'atendimento.id')
            ->whereNotNull('atendimento_tempo.recepcao_in')
            ->whereBetween('agendas.data', [$data_inicial, $data_final])
            ->whereIn('agendas.status', $status_agenda)
            ->groupBy('arena_nome');

        if (!empty($params['arena']))
            $sql->where('agendas.arena', $params['arena']);

        $arenas_recepcao = $sql->lists('total', 'arena_nome')->toArray();

        foreach ($arenas_agendamento as $row) {
            $key = $row['arena_nome'];

            $data['detalhado'][$key]['agendados'] = $row['total'];
            $data['detalhado'][$key]['atendimento'] = array_key_exists($key, $arenas_atendimento) ? $arenas_atendimento[$key] : 0;
            $data['detalhado'][$key]['faturista'] = array_key_exists($key, $arenas_faturista) ? $arenas_faturista[$key] : 0;
            $data['detalhado'][$key]['digitador'] = array_key_exists($key, $arenas_digitador) ? $arenas_digitador[$key] : 0;
            $data['detalhado'][$key]['recepcao'] = array_key_exists($key, $arenas_recepcao) ? $arenas_recepcao[$key] : 0;

        }

        return $data;
    }

    public static function IndicacoresProducao($params)
    {
        $lote = $params->get('lote');
        $status = $params->get('status');

        $status_in = [6, 8, 10, 98, 99];
        $arenas_lote = Arenas::getByLote($lote);

        $metricas = BI::metrasPorLoteLinhaCuidado($lote);
        $periodo = Util::periodoMesPorAnoMes($params->get('ano'), $params->get('mes'));

        $relatorio = null;
        foreach ($metricas as $k_linha_cuidado => $linha_cuidado_metas) {
            $sql = Agendas::select(
                "linha_cuidado.nome",
                DB::raw("sum(atendimento_procedimentos.quantidade * atendimento_procedimentos.multiplicador) total")
            )
                ->join('atendimento', 'atendimento.agenda', '=', 'agendas.id')
                ->join('atendimento_procedimentos', 'atendimento_procedimentos.atendimento', '=', 'atendimento.id')
                ->join('linha_cuidado_procedimentos', 'linha_cuidado_procedimentos.procedimento', '=', 'atendimento_procedimentos.procedimento')
                ->join('linha_cuidado', 'linha_cuidado.id', '=', 'linha_cuidado_procedimentos.linha_cuidado')
                ->where('linha_cuidado_procedimentos.linha_cuidado', $k_linha_cuidado)
                ->whereIn('agendas.arena', $arenas_lote)
                ->whereIn('atendimento.status', $status_in)
                ->whereBetween('agendas.data', [$periodo['start'], $periodo['end']])
                ->groupBy('linha_cuidado.nome')
                ->orderBy('linha_cuidado.nome', 'asc')
                ->get();

            $relatorio[$k_linha_cuidado] = [
                'linha_cuidado' => $sql[0]->nome,
                'min' => $linha_cuidado_metas['min'],
                'max' => $linha_cuidado_metas['max'],
                'total' => $sql[0]->total,
            ];
        }

        return $relatorio;
    }

    public static function PacientesDia($ano, $mes, $arena = null, $medico = null)
    {

        $periodo = Util::periodoMesPorAnoMes($ano, $mes);

        $sql = Agendas::select(
            [
                'linha_cuidado.nome AS linha_cuidado',

                'arenas.nome AS arenas_nome',
                'agendas.data AS agendas_data',
                'pacientes.nome AS paciente_nome',
                'profissionais.nome AS medico',
            ]
        )
            ->join('pacientes', 'pacientes.id', '=', 'agendas.paciente')
            ->join('arenas', 'arenas.id', '=', 'agendas.arena')
            ->join('linha_cuidado', 'linha_cuidado.id', '=', 'agendas.linha_cuidado')
            ->join('atendimento', 'atendimento.agenda', '=', 'agendas.id')
            ->leftJoin('profissionais', 'profissionais.id', '=', 'atendimento.medico')
            ->whereBetween('agendas.data', [$periodo['start'], $periodo['end']])
            ->orderBy('agendas.data', 'asc')// ->limit(10)
        ;


        if ($arena) {
            $sql->where('agendas.arena', $arena);
        }

        if ($medico) {
            $sql->where('profissionais.id', $medico);
            $sql->where('atendimento.medico', $medico);
        }

        return $sql->get()->toArray();
    }

    public static function RelatorioProducao2($data_inicial, $data_final, $lote = null)
    {

        $sql = Agendas::select(
            [
                'lotes.nome AS lote',
                'arenas.nome AS arena',
                'linha_cuidado.nome AS linha_cuidado',
                'procedimentos.sus AS cod_procedimento',
                'procedimentos.nome AS procedimento',
                'profissionais.cro AS crm',
                'profissionais.nome AS medico',
                'agendas.status AS status',
                DB::raw('DATE_FORMAT(agendas.data, "%d/%m/%Y") AS data_exame'),
                DB::raw('sum(atendimento_procedimentos.quantidade * atendimento_procedimentos.multiplicador) as total'),

            ]
        )
            ->join('atendimento', 'atendimento.agenda', '=', 'agendas.id')
            ->join('profissionais', 'atendimento.medico', '=', 'profissionais.id')
            ->join('arenas', 'arenas.id', '=', 'agendas.arena')
            ->join('linha_cuidado', 'linha_cuidado.id', '=', 'agendas.linha_cuidado')
            ->join('atendimento_procedimentos', 'atendimento_procedimentos.atendimento', '=', 'atendimento.id')
            ->join('procedimentos', 'atendimento_procedimentos.procedimento', '=', 'procedimentos.id')
            ->join('lotes_arena', 'arenas.id', '=', 'lotes_arena.arena')
            ->join('lotes', 'lotes.id', '=', 'lotes_arena.lote')
            ->whereBetween('agendas.data', [Util::Date2DB($data_inicial) . " 00:00:00", Util::Date2DB($data_final) . " 23:59:59"])
            ->whereIn('agendas.status', [6, 8, 10, 98, 99])
//            ->whereIn('agendas.status', [6, 8])
            ->orderBy('arenas.nome', 'asc')
            ->orderBy('agendas.data', 'asc')
            ->orderBy('procedimentos.nome', 'asc')
            ->groupBy(
                [
                    'lote',
                    'arena',
                    'linha_cuidado',
                    'cod_procedimento',
                    'procedimento',
                    'medico',
                    'status',
                    'data_exame',
                ]
            )//            ->limit(10)
        ;

        if (!empty($lote)) {
            $sql->where('lotes.id', $lote);
        }

        $res = $sql->get();

        return ($res->count()) ? $res : null;
    }

    public static function getPacienteAtendidosPeriodo($start, $end)
    {

        $data = Agendas::select(
            [
                'arenas.nome AS arena',
                'linha_cuidado.nome AS linha_cuidado',
                'agendas.tipo_atendimento AS tipo_atendimento',
                DB::raw('DATE_FORMAT(agendas.data, "%d/%m/%Y") AS data'),
                DB::raw('COUNT(agendas.paciente) as total'),
                DB::raw('COUNT(DISTINCT agendas.paciente) as total_consolidado'),
            ]
        )
            ->join('atendimento', 'atendimento.agenda', '=', 'agendas.id')
            ->join('arenas', 'arenas.id', '=', 'agendas.arena')
            ->join('linha_cuidado', 'linha_cuidado.id', '=', 'agendas.linha_cuidado')
            ->whereBetween('agendas.data', [$start . " 00:00:00", $end . " 23:59:59"])
            ->whereIN('atendimento.status', [6, 8, 10, 98, 99])
            ->orderBy('arenas.nome', 'asc')
            ->orderBy('linha_cuidado.nome', 'asc')
            ->orderBy('agendas.data', 'asc')
            ->groupBy(
                [
                    'arena',
                    'linha_cuidado',
                    'tipo_atendimento',
                    DB::raw('DATE_FORMAT(agendas.data, "%d/%m/%Y")')
                ]
            )
//            ->limit(10)
            ->get();

        return $data;
    }

    public static function getAbsenteismoPerdaPrimariaAgenda(array $date, $unidade = null, $especialidade = null)
    {
        $case_tipo_atendimento = [];
        foreach (Util::getTipoAtendimento() as $k => $row) {
            $case_tipo_atendimento[] = "WHEN agendas.tipo_atendimento = {$k} THEN '" . mb_strtoupper($row) . "'";
        }

        $sql = Agendas::select(
            [
                DB::raw('DATE(agendas.data) AS data'),
                'arenas.nome AS arena',
                'linha_cuidado.nome AS linha_cuidado',
                DB::raw('
                (
                    CASE
                        ' . implode('', $case_tipo_atendimento) . '
                        ELSE ""
                    END
                  ) AS tipo_atendimento

                '),
                DB::raw("ELT(WEEKDAY(DATE(agendas.data)) + 2, 'Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado') AS dias_semana"),
                DB::raw("(SELECT SUM(ofertas.quantidade) FROM ofertas WHERE ofertas.data = DATE(agendas.data) AND ofertas.linha_cuidado = agendas.linha_cuidado AND ofertas.arena = agendas.arena AND ofertas.status IN (1,2,3,4,14,13)) AS ofertas"),
                DB::raw('COUNT(agendas.id) AS agendamentos_geral'),
                DB::raw('COUNT(DISTINCT agendas.paciente) AS pacientes'),
                DB::raw('SUM(IF(agendas.import IS NOT NULL, 1 , 0)) AS agendamentos'),
                DB::raw('SUM(IF(agendas.status = 10 OR agendas.status = 6 OR agendas.status = 98 OR agendas.status = 99, 1 , 0)) AS atendimentos'),
                DB::raw('SUM(IF(agendas.status = 7 OR agendas.status = 2, 1 , 0)) AS faltas'),
                DB::raw('SUM(IF(agendas.status = 1, 1 , 0)) AS aberto'),
                DB::raw('SUM(IF(agendas.status = 2, 1 , 0)) AS atendimento'),
                DB::raw('SUM(IF(agendas.status = 3, 1 , 0)) AS remarcado'),
                DB::raw('SUM(IF(agendas.status = 4, 1 , 0)) AS encaixe'),
                DB::raw('SUM(IF(agendas.status = 5, 1 , 0)) AS nao_atendido'),
                DB::raw('SUM(IF(agendas.status = 0, 1 , 0)) AS cancelado'),
            ]
        )
            ->join('arenas', 'arenas.id', '=', 'agendas.arena')
            ->join('linha_cuidado', 'linha_cuidado.id', '=', 'agendas.linha_cuidado')
            ->whereBetween('agendas.data', array($date['start'], $date['end']))
            ->orderBy('agendas.id', 'DESC')
            ->groupBy(DB::raw('DATE(agendas.data)'), 'arenas.id', 'linha_cuidado.id', 'agendas.tipo_atendimento');

        if (!empty($unidade)) {
            $sql->where('arenas.id', $unidade);
        }

        if (!empty($especialidade)) {
            $sql->where('linha_cuidado.id', $especialidade);
        }

        $data = $sql->get();

        return !empty($data[0]) ? $data : null;
    }

    public static function getAtendimentoCondutas(array $date, $unidade = null, $especialidade = null)
    {

        $sql = Agendas::select(
            [
                'arenas.nome AS arena',
                DB::raw('DATE_FORMAT(agendas.data, "%d/%m/%Y") AS data_atendimento'),
                'pacientes.nome AS paciente',
                'pacientes.cns AS sus',
                'linha_cuidado.nome AS especialidade',
                'profissionais.nome AS medico',
                'tipo_atendimento.nome AS tipo_atendimento',
                'conduta_principal.nome AS conduta_principal',
                'conduta_secundaria.nome AS conduta_secundaria',
                'regulacao.nome AS regulacao',
                'atendimento_auxiliar.conduta_opcao AS lateralidade',
                DB::raw('atendimento_auxiliar.conduta_descricao AS descricao'),
            ]
        )
            ->join('arenas', 'arenas.id', '=', 'agendas.arena')
            ->join('linha_cuidado', 'linha_cuidado.id', '=', 'agendas.linha_cuidado')
            ->join('pacientes', 'pacientes.id', '=', 'agendas.paciente')
            ->join('atendimento', 'atendimento.agenda', '=', 'agendas.id')
            ->leftJoin('profissionais', 'atendimento.medico', '=', 'profissionais.id')
            ->join('atendimento_auxiliar', 'atendimento_auxiliar.atendimento', '=', 'atendimento.id')
            ->leftJoin('tipo_atendimento', 'tipo_atendimento.id', '=', 'atendimento.tipo_atendimento')
            ->leftJoin('condutas as conduta_principal', 'atendimento_auxiliar.conduta', '=', 'conduta_principal.id')
            ->leftJoin('condutas as conduta_secundaria', 'atendimento_auxiliar.conduta_secundaria', '=', 'conduta_secundaria.id')
            ->leftJoin('condutas as regulacao', 'atendimento_auxiliar.conduta_regulacao', '=', 'regulacao.id')
            ->whereBetween('agendas.data', array($date['start'], $date['end']))
            ->whereRaw('cast(atendimento_auxiliar.conduta as  unsigned integer) > 0')
            ->whereIn('agendas.status', [6, 8, 98, 99])
            ->orderBy('agendas.id', 'DESC');

        if (!empty($unidade)) {
            $sql->where('arenas.id', $unidade);
        }

        if (!empty($especialidade)) {
            $sql->where('linha_cuidado.id', $especialidade);
        }
//        $sql->limit(20);
        $data = $sql->get();

        return !empty($data[0]) ? $data : null;
    }

    public static function getAtendimentoContasConsulta(array $date, $unidade = null, $especialidade = null)
    {
        $case_tipo_atendimento = [];
        foreach (Util::getTipoAtendimento() as $k => $row) {
            $case_tipo_atendimento[] = "WHEN agendas.tipo_atendimento = {$k} THEN '" . mb_strtoupper($row) . "'";
        }

        $sql = Agendas::select(
            [
                'arenas.nome AS arena',
                DB::raw('DATE_FORMAT(agendas.data, "%d/%m/%Y") AS data_atendimento'),
                'profissionais.nome AS medico',
                'linha_cuidado.nome AS especialidade',
                DB::raw('
                (
                    CASE
                        ' . implode('', $case_tipo_atendimento) . '
                        ELSE ""
                    END
                  ) AS tipo_atendimento

                '),
                'conduta_principal.nome AS conduta_principal',
                DB::raw('COUNT(atendimento.id) as total'),
            ]
        )
            ->join('arenas', 'arenas.id', '=', 'agendas.arena')
            ->join('linha_cuidado', 'linha_cuidado.id', '=', 'agendas.linha_cuidado')
            ->join('atendimento', 'atendimento.agenda', '=', 'agendas.id')
            ->leftJoin('profissionais', 'atendimento.medico', '=', 'profissionais.id')
            ->leftJoin('atendimento_auxiliar', 'atendimento_auxiliar.atendimento', '=', 'atendimento.id')
            ->leftJoin('condutas as conduta_principal', 'atendimento_auxiliar.conduta', '=', 'conduta_principal.id')
            ->whereBetween('agendas.data', array($date['start'], $date['end']))
            ->whereIn('agendas.status', [6, 8, 98, 99])
            ->where('linha_cuidado.especialidade', '=', 2)
            ->groupBy([
                'arenas.nome',
                DB::raw('DATE_FORMAT(agendas.data, "%d/%m/%Y")'),
                'profissionais.nome',
                'linha_cuidado.nome',
                'atendimento.tipo_atendimento',
                'conduta_principal.nome'
            ])
            ->orderBy(DB::raw('DATE_FORMAT(agendas.data, "%d/%m/%Y")'), 'ASC')
            ->orderBy('arenas.nome', 'ASC')
            ->orderBy('profissionais.nome', 'ASC')
            ->orderBy('linha_cuidado.nome', 'ASC')
            ->orderBy('linha_cuidado.nome', 'ASC')
        ;

        if (!empty($unidade)) {
            $sql->where('arenas.id', '=', $unidade);
            $sql->where('agendas.arena', '=', $unidade);
        }

        if (!empty($especialidade)) {
            $sql->where('linha_cuidado.id', $especialidade);
        }

//        $sql->limit(10);
        $data = $sql->get();

        return !empty($data[0]) ? $data : null;
    }

    public static function getAtendimentoContasConsultaDiagnostico(array $date, $unidade = null, $especialidade = null)
    {
        $sql = Agendas::select(
            [
                'arenas.nome AS arena',
                DB::raw('DATE_FORMAT(agendas.data, "%d/%m/%Y") AS data_atendimento'),
                'profissionais.nome AS medico',
                'linha_cuidado.nome AS especialidade',
                'procedimentos.nome AS procedimento',
                DB::raw('SUM(atendimento_procedimentos.quantidade) as total_exames'),
                DB::raw('SUM(atendimento_procedimentos.quantidade * atendimento_procedimentos.multiplicador_medico) as total_com_multiplicador'),
                'atendimento_procedimentos.valor_medico as valor_unitario',
            ]
        )
            ->join('arenas', 'arenas.id', '=', 'agendas.arena')
            ->join('linha_cuidado', 'linha_cuidado.id', '=', 'agendas.linha_cuidado')
            ->join('atendimento', 'atendimento.agenda', '=', 'agendas.id')
            ->join('atendimento_procedimentos', 'atendimento_procedimentos.atendimento', '=', 'atendimento.id')
            ->join('procedimentos', 'atendimento_procedimentos.procedimento', '=', 'procedimentos.id')
            ->leftJoin('profissionais', 'atendimento.medico', '=', 'profissionais.id')
            ->whereBetween('agendas.data', array($date['start'], $date['end']))
            ->whereIn('agendas.status', [6, 8, 98, 99])
            ->where('linha_cuidado.especialidade', '=', 1)
            ->groupBy([
                'arenas.nome',
                DB::raw('DATE_FORMAT(agendas.data, "%d/%m/%Y")'),
                'profissionais.nome',
                'linha_cuidado.nome',
                'procedimentos.nome',
                'atendimento_procedimentos.valor_medico',
            ])
            ->orderBy(DB::raw('DATE_FORMAT(agendas.data, "%d/%m/%Y")'), 'ASC')
            ->orderBy('arenas.nome', 'ASC')
            ->orderBy('profissionais.nome', 'ASC')
            ->orderBy('linha_cuidado.nome', 'ASC')
            ->orderBy('procedimentos.nome', 'ASC')
        ;

        if (!empty($unidade)) {
            $sql->where('arenas.id', $unidade);
        }

        if (!empty($especialidade)) {
            $sql->where('linha_cuidado.id', $especialidade);
        }

//        $sql->limit(10);
        $data = $sql->get();

        return !empty($data[0]) ? $data : null;
    }

}