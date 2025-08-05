<?php

namespace App\Console\Commands;

use App\Agendas;
use App\AtendimentoProcedimentos;
use App\Atendimentos;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AgendasStatusFinalizadoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cies:agendas-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atualiza o status das agendas para finalizado 99 se estiver 100% faturado';

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
        $atendimentos = Atendimentos::select(['id', 'agenda'])->where('status', 98)->limit(20000)->orderBy('id', 'desc')->get();

        if (!empty($atendimentos[0])) {
            foreach ($atendimentos AS $atendimento) {
                $atendimento_procedimentos = AtendimentoProcedimentos::where('faturado', 0)->where('atendimento', $atendimento->id)->get();

                if (empty($atendimento_procedimentos[0])) {
                    DB::transaction(function () use ($atendimento) {

                        try {
                            Agendas::where('id', $atendimento->agenda)->update(['status' => 99]);

                            $atendimento->status = 99;
                            $atendimento->save();

                            DB::commit();
                        } catch (\Exception $e) {
                            DB::rollback();
                        }

                        echo "\n" . date('Y-m-d H:i:s') . " ATENDIMENTO => {$atendimento->id}";
                    });
                }
            }
        }
    }
}
