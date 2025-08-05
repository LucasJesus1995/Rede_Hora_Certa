<?php

namespace App\Console\Commands;

use App\Agendas;
use App\AtendimentoProcedimentos;
use App\Atendimentos;
use App\AtendimentoStatus;
use App\ContratoProcedimentos;
use App\Faturamento;
use App\FaturamentoLotes;
use App\FaturamentoProcedimento;
use App\Http\Helpers\Util;
use App\Http\Rules\Faturamento\Procedimentos;
use App\LinhaCuidadoProcedimentos;
use App\Lotes;
use App\LotesArena;
use App\LotesLinhaCuidado;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FaturamentoCommand extends Command
{
    protected $signature = 'cies:faturamento';
    protected $description = 'Faturamento mensal';
    protected $ano;
    protected $mes;
    protected $out;
    protected $debug = true;
    protected $data_final_competencia = null;
    protected $data_limite_retroativo = null;
    protected $log = array();
    private $_faturamento;
    private $_old_agenda = 0;
    private $_old_atendimento = 0;
    public static $_inicio_faturamento = '2017-03-01 00:00:00';
    private $_limite_faturamento = 200;

    public function __construct()
    {
        ini_set('memory_limit', -1);

        $this->out = new \Symfony\Component\Console\Output\ConsoleOutput(4);

        parent::__construct();
    }

    public function handle()
    {
        $this->_atualizaProcedimentosMultiplicador();

        $this->_log("---------------------------------------------------------------------------------------------------");
        $this->_log("---------------------------------------------------------------------------------------------------");

        try {
            $faturamentos = $this->_getFaturamento();

            if (count($faturamentos)) {

                foreach ($faturamentos AS $faturamento) {

                    $this->ano = $faturamento->ano;
                    $this->mes = $faturamento->mes;

                    $ultimo_dia = Util::getUltimosDiaMes($this->ano, $this->mes);
                    $this->data_final_competencia = Carbon::create($this->ano, $this->mes, $ultimo_dia, 23, 59, 59)->toDateTimeString();
                    $this->data_limite_retroativo = Carbon::create($this->ano, $this->mes, 01, 0, 0, 0)->subMonth(3)->toDateTimeString();

                    $this->_faturamento = $faturamento;

                    $faturamento_lotes = FaturamentoLotes::where('faturamento', $faturamento->id)->get();

                    foreach ($faturamento_lotes AS $faturamento_lote) {
                        $lote = Lotes::find($faturamento_lote->lote);

                        $this->_log("---------------------------------------------------------------------------------------------------");
                        $this->_log('-------------------------------------------- ' . $lote->nome . ' ------------------------------------------');

                        $demandas = Procedimentos::getProcedimentosContratoByLote($lote->id);
                        if (!empty($demandas)) {
                            $total_por_sus = $this->_getProcedimentosAFaturado($faturamento_lote->faturamento, $faturamento_lote->lote);

                            foreach ($demandas AS $demanda) {
                                $codigo_procedimento = $demanda->sus;
                                $total = $demanda->demanda;

                                $produzido = array_key_exists($codigo_procedimento, $total_por_sus) ? $total_por_sus[$codigo_procedimento] : 0;
                                $a_faturar = $total - $produzido;

                                $this->_log('=====> ' . str_pad($codigo_procedimento, 10, ' ', STR_PAD_LEFT) . " ===== (" . str_pad($total, 6, ' ', STR_PAD_LEFT) . " - " . str_pad($produzido, 6, ' ', STR_PAD_LEFT) . ") = {$a_faturar}");
                                if ($a_faturar > 0) {
                                    $atendimentos_procedimentos = $this->faturarProcedimentosBySUS($codigo_procedimento, $a_faturar, $lote->id);

                                    if (!empty($atendimentos_procedimentos[0])) {
                                        foreach ($atendimentos_procedimentos AS $atendimentos_procedimento) {
                                            $contador = 0;
                                            DB::transaction(function () use ($atendimentos_procedimento, $lote, &$contador) {

                                                $this->_updateAgendaStatus($atendimentos_procedimento->agenda, 98);
                                                $this->_updateAtendimentoStatus($atendimentos_procedimento->atendimento, 98);
                                                $this->_updateAtendimentoProcedimentoFaturado($atendimentos_procedimento->id);

                                                $_faturamento_procedimento = new FaturamentoProcedimento();
                                                $_faturamento_procedimento->faturamento = $this->_faturamento->id;
                                                $_faturamento_procedimento->lote = $lote->id;
                                                $_faturamento_procedimento->linha_cuidado = $atendimentos_procedimento->linha_cuidado;
                                                $_faturamento_procedimento->atendimento_procedimento = $atendimentos_procedimento->id;
                                                $_faturamento_procedimento->quantidade = $atendimentos_procedimento->quantidade;
                                                $_faturamento_procedimento->save();

                                                $contador++;
                                            });
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            $this->_log("---------------------------------------------------------------------------------------------------");
            $this->_log("---------------------------------------------------------------------------------------------------");
            $this->_log("---------------------------------------------------------------------------------------------------");

            $this->_log('Fim do processamento');
        } catch (\Exception $e) {

            exit("<pre>" . print_r($e->getMessage(), true) . "</pre>");
            $this->_log("------------------------------ Exception ------------------------------");
            $this->_log($e->getMessage());
            $this->_log($e->getFile());
            $this->_log($e->getLine());
            $this->_log($e->getTraceAsString());
            $this->_log($e->getTrace());
            $this->_log("------------------------------ Exception ------------------------------");
        }
    }

    private function procedimentoFaturado($lote, $linha_cuidado)
    {
        $faturamento_procedimento = FaturamentoProcedimento::getFaturamentoQuantidade($lote, $linha_cuidado, $this->_faturamento->id);

        return (int)!empty($faturamento_procedimento) ? $faturamento_procedimento : 0;
    }

    private function _getFaturamento()
    {
        return Faturamento::where('status', 2)->get();;
    }

    private function _log($text, $type = 1)
    {
        if (is_string($text)) {
            $message = date('Y-m-d H:i:s') . " _ " . $text;

            $this->log[] = $message;
            if ($this->debug) {
                $this->out->writeln($message, $type);
            }
        }
    }

    private function _getProcedimentosAFaturado($faturamento, $lote)
    {
        $procedimentos_faturamento = FaturamentoProcedimento::select(
            [
                'procedimentos.sus',
                DB::raw('SUM(faturamento_procedimentos.quantidade) AS total')
            ]
        )
            ->join('atendimento_procedimentos', 'atendimento_procedimentos.id', '=', 'faturamento_procedimentos.atendimento_procedimento')
            ->join('procedimentos', 'procedimentos.id', '=', 'atendimento_procedimentos.procedimento')
            ->where('faturamento_procedimentos.faturamento', $faturamento)
            ->where('faturamento_procedimentos.lote', $lote)
            ->groupBy('procedimentos.sus')
            ->lists('total', 'sus');

        return !empty($procedimentos_faturamento) ? $procedimentos_faturamento->toArray() : [];
    }

    private function faturarProcedimentosBySUS($procedimento_sus, $maximo, $lote)
    {
        $maximo = ($maximo > $this->_limite_faturamento) ? $this->_limite_faturamento : $maximo;

        $sql = Atendimentos::select(
            [
                'atendimento_procedimentos.id',
                'atendimento.id AS atendimento',
                'agendas.id AS agenda',
                'agendas.linha_cuidado AS linha_cuidado',
                DB::raw('(atendimento_procedimentos.quantidade * atendimento_procedimentos.multiplicador) as quantidade'),
            ]
        )
            ->join('agendas', 'agendas.id', '=', 'atendimento.agenda')
            ->join('atendimento_procedimentos', 'atendimento_procedimentos.atendimento', '=', 'atendimento.id')
            ->join('procedimentos', 'procedimentos.id', '=', 'atendimento_procedimentos.procedimento')
            ->join('lotes_arena', 'lotes_arena.arena', '=', 'agendas.arena')
            ->whereBetween('agendas.data', [$this->data_limite_retroativo, $this->data_final_competencia])
            ->whereIn('agendas.status', array(6, 98))
            ->whereNotIn('atendimento.status', array(99))
            ->whereNotNull('atendimento_procedimentos.faturista')
            ->whereNotNull('atendimento.medico')
            ->where('atendimento_procedimentos.faturado', 0)
            ->where('procedimentos.sus', $procedimento_sus)
            ->where('lotes_arena.lote', $lote)
            ->orderBy('agendas.id', 'asc')
            ->orderBy('atendimento.id', 'asc')
            ->orderBy('atendimento_procedimentos.id', 'asc')
            ->groupBy(
                [
                    'atendimento_procedimentos.id',
                    'atendimento.id',
                    'agendas.id'
                ]
            )
            ->limit($maximo);

        return $sql->get();
    }

    private function _updateAgendaStatus($id, $status)
    {
        Agendas::where('id', $id)
            ->update(
                [
                    'status' => $status
                ]
            );

    }

    private function _updateAtendimentoStatus($id, $status)
    {
        Atendimentos::where('id', $id)
            ->update(['status' => $status]);

        AtendimentoStatus::setStatus($id, $status);
    }

    private function _updateAtendimentoProcedimentoFaturado($id)
    {
        AtendimentoProcedimentos::where('id', $id)
            ->update(
                [
                    'faturado' => true
                ]
            );
    }

    /**
     * Atualiza a quantidade do multiplicador caso o procedimento não tenha sido faturamento
     */
    private function _atualizaProcedimentosMultiplicador($limit = 1000)
    {
        $atendimento_procedimentos = AtendimentoProcedimentos::join('atendimento', 'atendimento.id', '=', 'atendimento_procedimentos.atendimento')
            ->join('procedimentos', 'procedimentos.id', '=', 'atendimento_procedimentos.procedimento')
            ->join('agendas', 'agendas.id', '=', 'atendimento.agenda')
            ->where('procedimentos.multiplicador', '>', 1)
            ->where('agendas.data', '>=', self::$_inicio_faturamento)
            ->whereIn('agendas.status', [6])
            ->whereIn('atendimento.status', [6])
            ->where('atendimento_procedimentos.faturado', 0)
            ->select(['atendimento_procedimentos.id', 'procedimentos.multiplicador'])
            ->get();

        if (count($atendimento_procedimentos)) {
            $groups = [];
            foreach ($atendimento_procedimentos AS $atendimento_procedimento) {
                $groups[$atendimento_procedimento->multiplicador][] = $atendimento_procedimento->id;
            }

            if (count($groups)) {
                foreach ($groups AS $multi => $ids) {
                    AtendimentoProcedimentos::whereIn('id', $ids)
                        ->update(['multiplicador' => $multi]);
                }
            }

        }
    }

    private function checkFaturamentoDuplicado()
    {
        if (in_array(date('N'), array(1, 5))) {
            DB::select("DELETE a FROM faturamento_procedimentos AS a, faturamento_procedimentos AS b WHERE a.atendimento_procedimento=b.atendimento_procedimento AND a.id > b.id;");
            $this->_log("Removendo possiveis duplicações no processo de faturamento procedimentos");
        }
    }
}
