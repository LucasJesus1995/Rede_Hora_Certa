<?php

namespace App\Console\Commands;

use App\Agendas;
use App\AtendimentoProcedimentos;
use App\Atendimentos;
use App\Faturamento;
use App\FaturamentoProcedimento;
use App\Http\Rules\Faturamento\Procedimentos;
use App\Lotes;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Exception;

class FaturamentoProcedimentosMetasCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cies:faturamento-procedimentos-metas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Analisar quais procedimentos faturamento ultrapassaram a metas e efetua correção';
    private $lote = 7;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $faturamento = Faturamento::where('status', 2)->orderBy('id', 'desc')->limit(1)->get();

        if (!empty($faturamento[0])) {
            $faturamento = $faturamento[0];

            $lotes = Lotes::where('ativo', 1)->get();
            foreach ($lotes as $lote) {
                $this->lote = $lote;
                $demandas = Procedimentos::getProcedimentosContratoByLote($this->lote->id);

                if ($demandas) {
                    foreach ($demandas as $demanda) {
                        $procedimentos_faturados = $this->getProcedimentoFaturado($faturamento->id, $demanda);
                    }
                }
            }
        }
    }

    private function getProcedimentoFaturado($faturamento, $demanda)
    {
        $data = FaturamentoProcedimento::select(
            [
                DB::raw('SUM(faturamento_procedimentos.quantidade) AS quantidade')
            ]
        )
            ->join('atendimento_procedimentos', 'atendimento_procedimentos.id', '=', 'faturamento_procedimentos.atendimento_procedimento')
            ->join('procedimentos', 'procedimentos.id', '=', 'atendimento_procedimentos.procedimento')
            ->where('procedimentos.sus', $demanda->sus)
            ->where('faturamento', $faturamento)
            ->where('lote', $this->lote->id)
            ->having('quantidade', '>', $demanda->demanda)
            ->get();

        if (!empty($data[0])) {
            $limite_ultrapassado = $data[0]->quantidade - $demanda->demanda;
            $faturamento_procedimentos = $this->getFaturamentoProcedimento($faturamento, $demanda, $limite_ultrapassado);

            if (!empty($faturamento_procedimentos[0])) {
                foreach ($faturamento_procedimentos as $faturamento_procedimento) {
                    DB::transaction(function () use ($faturamento_procedimento) {

                        $atendimento_procedimento = AtendimentoProcedimentos::find($faturamento_procedimento->atendimento_procedimento);
                        $atendimento_procedimento->faturado = 0;
                        $atendimento_procedimento->save();

                        $atendimento = Atendimentos::find($atendimento_procedimento->atendimento);
                        $atendimento->status = 98;
                        $atendimento->save();

                        Agendas::where('id', $atendimento->agenda)->update(['status' => 98]);

                        $faturamento_procedimento->delete();
                    });
                }
            }
        }
    }

    private function getFaturamentoProcedimento($faturamento, $demanda, $limite_ultrapassado)
    {
        $data = FaturamentoProcedimento::select(
            [
                'faturamento_procedimentos.*'
            ]
        )
            ->join('atendimento_procedimentos', 'atendimento_procedimentos.id', '=', 'faturamento_procedimentos.atendimento_procedimento')
            ->join('procedimentos', 'procedimentos.id', '=', 'atendimento_procedimentos.procedimento')
            ->where('procedimentos.sus', $demanda->sus)
            ->where('faturamento', $faturamento)
            ->where('lote', $this->lote->id)
            ->orderBy('faturamento_procedimentos.id', 'desc')
            ->orderBy('atendimento_procedimentos.id', 'desc')
            ->limit($limite_ultrapassado)
            ->get();

        return $data;
    }
}
