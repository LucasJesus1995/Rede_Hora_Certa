<?php

namespace App\Console\Commands;

use App\AtendimentoProcedimentos;
use App\FaturamentoProcedimento;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RestoreFaturamentoCommand extends Command
{

    protected $signature = 'cies:restore-faturamento {faturamento}';

    protected $description = 'Restura o Faturamento';

    private $faturamento = null;

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->faturamento = $this->argument('faturamento');

        $this->restoreFaturamento();
    }

    private function restoreFaturamento()
    {

        for ($i = 0; $i < 100; $i++) {

            DB::transaction(function () {

                try {
                    $limit = 10000;

                    $faturamento_procedimentos = FaturamentoProcedimento::where('faturamento', $this->faturamento)->limit($limit)->get();

                    if (count($faturamento_procedimentos) > 0) {
                        $ids = array_column($faturamento_procedimentos->toArray(), 'atendimento_procedimento');

                        AtendimentoProcedimentos::whereIn('id', $ids)->update(['faturado' => 0]);
                        FaturamentoProcedimento::whereIn('id', array_column($faturamento_procedimentos->toArray(), 'id'))->delete();
                    }

                    DB::commit();
                } catch (\Exception $exception) {
                    DB::rollBack();

                    print("<pre>" . print_r($exception->getMessage(), true) . "</pre>");
                }

            });

            echo "\n {$i} - " . date('Y-m-d H:i:s');
        }

    }
}
