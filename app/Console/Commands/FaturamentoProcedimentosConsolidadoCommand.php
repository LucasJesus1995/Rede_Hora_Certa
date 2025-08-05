<?php

namespace App\Console\Commands;

use App\Agendas;
use App\Arenas;
use App\AtendimentoProcedimentos;
use App\Atendimentos;
use App\AtendimentoStatus;
use App\Faturamento;
use App\FaturamentoLotes;
use App\FaturamentoProcedimento;
use App\Http\Helpers\Util;
use App\LinhaCuidadoProcedimentos;
use App\Lotes;
use App\LotesArena;
use App\LotesLinhaCuidado;
use App\Procedimentos;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Logger\ConsoleLogger;

class FaturamentoProcedimentosConsolidadoCommand extends Command
{
    protected $signature = 'cies:faturamento-procedimentos-consolidado';
    protected $description = '';

    private $_faturamento = 22;
    /**
     * @var string
     */
    private $date_limite;


    public function __construct()
    {
        ini_set('memory_limit', -1);

        parent::__construct();
    }

    public function handle()
    {
        $this->date_limite = Carbon::now()->subDay(3)->format('Y-m-d H:i:s');

        //$this->ultrassomNaoConsolidado();
        $this->ultrassomSemConsultaEspecializada();
    }

    protected function ultrassomSemConsultaEspecializada()
    {
        $data = FaturamentoProcedimento::select("faturamento_procedimentos.id")
            ->join('atendimento_procedimentos', 'atendimento_procedimentos.id', '=', 'faturamento_procedimentos.atendimento_procedimento')
            ->where('faturamento_procedimentos.faturamento', '>=', 24)
            ->where('faturamento_procedimentos.linha_cuidado', 7)
            ->where('atendimento_procedimentos.procedimento', 11)
            ->where('faturamento_procedimentos.status', 1)
            ->where('faturamento_procedimentos.created_at', '>=', $this->date_limite)
            ->get()
            ->lists('id');

        if (!empty($data[0])) {
            FaturamentoProcedimento::whereIn('id', $data->toArray())->update(['faturamento_procedimentos.status' => 0]);
        }

    }

    protected function ultrassomNaoConsolidado()
    {
        $sql = FaturamentoProcedimento::select(
            [
                'faturamento_procedimentos.*'
            ]
        )
            ->join('atendimento_procedimentos', 'atendimento_procedimentos.id', '=', 'faturamento_procedimentos.atendimento_procedimento')
            ->join('atendimento', 'atendimento.id', '=', 'atendimento_procedimentos.atendimento')
            ->join('agendas', 'agendas.id', '=', 'atendimento.agenda')
            ->whereIn('atendimento_procedimentos.procedimento', [11])
            ->where('agendas.linha_cuidado', 7)
            ->where('faturamento_procedimentos.faturamento', '>=', $this->_faturamento)
            ->whereIn('atendimento_procedimentos.atendimento', function ($query) {
                $query->select('atendimento_procedimentos.atendimento')
                    ->from('atendimento_procedimentos')
                    ->join('atendimento', 'atendimento.id', '=', 'atendimento_procedimentos.atendimento')
                    ->join('procedimentos', 'procedimentos.id', '=', 'atendimento_procedimentos.procedimento')
                    ->where('procedimentos.forma_faturamento', 2);
            })
            ->orderBy('atendimento.id', 'desc')
            ->get();

        if (!empty($sql)) {
            foreach ($sql AS $row) {
                $row->status = 0;
                $row->save();
            }
        }
    }

}
