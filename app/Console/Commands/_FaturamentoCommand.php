<?php

namespace App\Console\Commands;

use App\Agendas;
use App\AtendimentoProcedimentos;
use App\Atendimentos;
use App\AtendimentoStatus;
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
    protected $log = array();
    private $_faturamento;
    private $_old_agenda = 0;
    private $_old_atendimento = 0;
    public static $_inicio_faturamento = '2017-03-01 00:00:00';
    private $_limite_faturamento = 5000;

    public function __construct()
    {
        ini_set('memory_limit', -1);

        $this->out = new \Symfony\Component\Console\Output\ConsoleOutput(4);

        parent::__construct();
    }

    public function handle()
    {
        //$this->_removeAntigos();
        $this->_atualizaProcedimentosMultiplicador();

        $this->_log("---------------------------------------------------------------------------------------------------");
        $this->_log("---------------------------------------------------------------------------------------------------");

        try {
            $faturamentos = $this->_getFaturamento();

            if (count($faturamentos)) {

                foreach ($faturamentos AS $faturamento) {

                    $this->ano = $faturamento->ano;
                    $this->mes = $faturamento->mes;

                    $this->_faturamento = $faturamento;

                    $faturamento_lotes = FaturamentoLotes::where('faturamento', $faturamento->id)->get();

                    foreach ($faturamento_lotes AS $faturamento_lote) {
                        $lote = Lotes::find($faturamento_lote->lote);

                        $this->_log("---------------------------------------------------------------------------------------------------");
                        $this->_log('-------------------------------------------- ' . $lote->nome . ' ------------------------------------------');

                        $arenas = LotesArena::getArenasByLote($lote->id);

                        $_arenas = null;
                        if ($arenas) {
                            foreach ($arenas AS $row) {
                                $_arenas[] = $row->arena;
                            }
                        }

                        if (!empty($_arenas)) {

                            $linhas_cuidado_lote = LotesLinhaCuidado::getLinhaCuidadoByFaturamentoLote($faturamento_lote->id);

                            if (count($linhas_cuidado_lote)) {

                                foreach ($linhas_cuidado_lote AS $linha_cuidado) {
                                    $this->_log("Processando {$lote->nome} > ARENA (" . implode(",", $_arenas) . ") > LINHA_CUIDADO {$linha_cuidado->linha_cuidado}");

                                    $maximo = $linha_cuidado->maximo;

                                    $qtd_ja_faturadas = $this->procedimentoFaturado($lote->id, $linha_cuidado->linha_cuidado);

                                    $quantidade_a_fatura = $maximo - $qtd_ja_faturadas;

                                    $this->_log("_ Faturados = ({$qtd_ja_faturadas} de {$maximo}) = {$quantidade_a_fatura}");

                                    $quantidade_a_fatura = $this->_limite_faturamento;

                                    if ($quantidade_a_fatura < 1) {
                                        continue;
                                    }

                                    $atendimento_procedimentos = $this->_atendimentoProcedimentos($_arenas, $linha_cuidado->linha_cuidado, $quantidade_a_fatura);

                                    if ($atendimento_procedimentos) {
                                        DB::transaction(function () use ($atendimento_procedimentos, $quantidade_a_fatura, $lote, $linha_cuidado) {

                                            $contador = 0;

                                            $this->_log("_ Faturar = " . count($atendimento_procedimentos));

                                            foreach ($atendimento_procedimentos AS $atendimento_procedimento) {
                                                if ($contador >= $quantidade_a_fatura) {
                                                    continue;
                                                }

                                                $agenda = $atendimento_procedimento->agenda;
                                                $atendimento = $atendimento_procedimento->atendimento;

                                                $this->_updateAgendaStatus($atendimento_procedimento->agenda, 98);
                                                $this->_updateAtendimentoStatus($atendimento_procedimento->atendimento, 98);
                                                $this->_updateAtendimentoProcedimentoFaturado($atendimento_procedimento->id);

                                                $_faturamento_procedimento = new FaturamentoProcedimento();
                                                $_faturamento_procedimento->faturamento = $this->_faturamento->id;
                                                $_faturamento_procedimento->lote = $lote->id;
                                                $_faturamento_procedimento->linha_cuidado = $atendimento_procedimento->linha_cuidado;
                                                $_faturamento_procedimento->atendimento_procedimento = $atendimento_procedimento->id;
                                                $_faturamento_procedimento->quantidade = $atendimento_procedimento->quantidade;
                                                $_faturamento_procedimento->save();

                                                for ($i = 1; $i <= $atendimento_procedimento->quantidade; $i++) {
                                                    $contador++;
                                                }

                                                if ($agenda != $this->_old_agenda && $this->_old_agenda != 0) {
                                                    //$this->_updateAgendaStatus($this->_old_agenda, 99);
                                                    //$this->_updateAtendimentoStatus($this->_old_atendimento, 99);
                                                }

                                                $this->_old_agenda = $agenda;
                                                $this->_old_atendimento = $atendimento;

                                            }

                                            $this->_log("_ Faturado = " . $contador);
                                        });
                                    }
                                    $this->_log('');

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

//        $this->call("cies:faturamento-procedimentos-consolidado");
//        $this->checkFaturamentoDuplicado();
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

    private function _atendimentoProcedimentos($arenas, $linha_cuidado, $maximo)
    {
        $data_fim = Carbon::now()->subDay()->toDateTimeString();
        $ultimo_dia = Util::getUltimosDiaMes($this->ano, $this->mes);

        $mes = Util::StrPadLeft($this->mes, 2, 0);

        $data_final_competencia = "{$this->ano}-{$mes}-{$ultimo_dia} 23:59:59";

        $retroativo = Carbon::createFromDate($this->ano, $mes, 01)->subMonth(3);
        $data_limite_retroativo = $retroativo->format("Y-m-d 00:00:00");

        $procedimentos_faturamento = Procedimentos::getProcedimentosFaturados();

        $_maximo = ($maximo > 5000) ? 5000 : $maximo;

        $procedimentos = LinhaCuidadoProcedimentos::where('linha_cuidado', $linha_cuidado)
            ->whereIn('procedimento', $procedimentos_faturamento)
            ->get()
            ->lists('procedimento');

        $procedimentos_patologia = \App\Procedimentos::getProcedimentoPatologiaByLinhaCuidadoGrupo($linha_cuidado);

        $_procedimentos = array_merge($procedimentos_patologia, $procedimentos->toArray());

        $sql = Atendimentos::select(
            [
                'atendimento_procedimentos.id',
                DB::raw('(atendimento_procedimentos.quantidade * atendimento_procedimentos.multiplicador) as quantidade'),
                'atendimento.id AS atendimento',
                'agendas.linha_cuidado AS linha_cuidado',
                'agendas.id AS agenda'
            ]
        )
            ->join('agendas', 'agendas.id', '=', 'atendimento.agenda')
            ->join('atendimento_procedimentos', 'atendimento_procedimentos.atendimento', '=', 'atendimento.id')
            ->join('linha_cuidado_procedimentos', 'linha_cuidado_procedimentos.procedimento', '=', 'atendimento_procedimentos.procedimento')
            //->whereBetween('agendas.data', [self::$_inicio_faturamento, $data_fim])
            ->whereBetween('agendas.data', [$data_limite_retroativo, $data_fim])
            ->where('agendas.data', '<=', $data_final_competencia)
            ->whereIn('agendas.arena', $arenas)
            ->whereIn('agendas.status', array(6, 98))
            ->whereNotIn('atendimento.status', array(99))
            ->whereIn('atendimento_procedimentos.procedimento', $_procedimentos)
            ->whereNotNull('atendimento_procedimentos.faturista')
            ->where('atendimento_procedimentos.faturado', 0)
            ->where('agendas.linha_cuidado', $linha_cuidado)
            ->where('linha_cuidado_procedimentos.linha_cuidado', $linha_cuidado)
            ->whereIn('linha_cuidado_procedimentos.procedimento', $procedimentos)
            ->orderBy('atendimento_procedimentos.id', 'asc')
            ->groupBy(
                [
                    'atendimento_procedimentos.id',
                    'atendimento.id',
                    'agendas.id'
                ]
            )
            ->limit($_maximo);

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
     * Remove os procedimentos antigos para não entrar no faturamento.
     * Foi usado para iniciar o sistema;
     */
    private function _removeAntigos()
    {
        for ($i = 0; $i < 300; $i++) {
            $atendimento_procedimentos = AtendimentoProcedimentos::select(['atendimento_procedimentos.*'])
                ->join('atendimento', 'atendimento.id', '=', 'atendimento_procedimentos.atendimento')
                ->join('agendas', 'agendas.id', '=', 'atendimento.agenda')
                ->where('atendimento_procedimentos.faturado', 0)
                ->where('agendas.data', '<', self::$_inicio_faturamento)
                ->limit(10000)
                ->get();

            if (count($atendimento_procedimentos)) {
                $ids = [];
                foreach ($atendimento_procedimentos AS $row) {
                    $ids[] = $row->id;
                }

                if (count($ids)) {
                    AtendimentoProcedimentos::whereIn('id', $ids)
                        ->update(['faturado' => 1]);
                }
            } else {
                break;
            }
        }
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
            //->limit()
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
