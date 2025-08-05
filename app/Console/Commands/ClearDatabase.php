<?php

namespace App\Console\Commands;

use App\AtendimentoProcedimentos;
use App\Atendimentos;
use App\AtendimentoStatus;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClearDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cies:clear-db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rotinas de manutenção de tabelas e banco de dados';
    /**
     * @var string
     */
    private $date_limite;

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
        $this->date_limite = Carbon::now()->subDays(1)->format('Y-m-d H:i:s');

        $this->atendimentos_procedimentos();
        $this->atendimentos_status();
        $this->atendimentos();

        echo "\n>>> ".date('Y-m-d H:i:s');
        echo "\n";
    }

    private function atendimentos_status()
    {
        echo "\n>>> ".date('Y-m-d H:i:s')." atendimentos_status";

        DB::delete("DELETE a FROM atendimento_status AS a, atendimento_status AS b WHERE a.atendimento=b.atendimento AND a.status = b.status AND a.id > b.id AND a.created_at >= '{$this->date_limite}';");
    }

    private function atendimentos_procedimentos()
    {
        echo "\n>>> ".date('Y-m-d H:i:s')." atendimentos_procedimentos";

        DB::delete("DELETE a FROM atendimento_procedimentos a, atendimento_procedimentos b WHERE a.atendimento = b.atendimento AND a.procedimento = b.procedimento  AND a.id > b.id AND a.faturado = 0 AND a.created_at >= '{$this->date_limite}';");
    }

    private function atendimentos()
    {
        echo "\n>>> ".date('Y-m-d H:i:s')." atendimentos";

        $data = DB::select("SELECT DISTINCT a.id FROM atendimento AS a, atendimento AS b WHERE a.agenda=b.agenda AND a.id > b.id AND a.status IN (2)  AND a.created_at >= '{$this->date_limite}'");

        $tables = [
            'atendimento_status',
            'atendimento_anamnense_respostas',
            'atendimento_tempo',
            'atendimento_check_list',
            'atendimento_medicamentos',
            'atendimento_procedimentos'
        ];

        foreach ($data AS $atendimento) {
            DB::transaction(function () use ($data, $atendimento, $tables) {
                try {
                    foreach ($tables AS $table) {
                        DB::delete("DELETE FROM {$table} WHERE atendimento = {$atendimento->id}");
                    }

                    DB::delete("DELETE FROM atendimento WHERE id = {$atendimento->id}");
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                }
            });
        }
    }

}
