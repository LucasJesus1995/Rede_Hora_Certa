<?php

namespace App\Console\Commands\Faturamento;

use App\Agendas;
use App\AtendimentoProcedimentos;
use App\Atendimentos;
use App\Console\Commands\AtendimentosNaoAtendidoCommand;
use App\Ofertas;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AjusteStatusProcedimentoAgendaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cies:faturamento-status-procedimento-agenda';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

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
        $data = Agendas::distinct()->select(
            [
                'agendas.id as agenda',
                'atendimento.id as atendimento',
            ]
        )
            ->join('atendimento', 'atendimento.agenda', '=', 'agendas.id')
            ->join('atendimento_procedimentos', 'atendimento.id', '=', 'atendimento_procedimentos.atendimento')
            ->where('atendimento_procedimentos.faturado', 1)
            ->whereNotNull('atendimento_procedimentos.medico')
//            ->whereNotIn('agendas.status', [98, 99])
            ->whereIn('agendas.status', [6])
            ->limit(2500)
            ->get();

        if (!empty($data[0])) {
            foreach ($data as $row) {
                $this->checkStatus($row->agenda, $row->atendimento);
            }
        }
    }

    private function checkStatus($agenda, $atendimento)
    {
        $atendimento_procedimentos = AtendimentoProcedimentos::where('atendimento', $atendimento)->where('faturado', 0)->get();
        $status = 99;
        if (!empty($atendimento_procedimentos[0]))
            $status = 98;

        DB::transaction(function () use ($agenda, $atendimento, $status) {
            try {
                Agendas::where('id', $agenda)->update(['status' => $status ]);
                Atendimentos::where('id', $atendimento)->update(['status' => $status]);

                DB::commit();
            } catch (\Exception $e) {
                print($e->getMessage());
                DB::rollBack();
            }
        });
    }
}
