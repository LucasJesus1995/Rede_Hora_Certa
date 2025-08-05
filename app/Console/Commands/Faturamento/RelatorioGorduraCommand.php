<?php

namespace App\Console\Commands\Faturamento;

use App\Atendimentos;
use App\Faturamento;
use App\FaturamentoProcedimento;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RelatorioGorduraCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cies:relatorio-gordura';

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
        echo date('Y-m-d H:i:s');

        $this->createTable();
        $this->removeNaoFaturados();
        $this->processaGordura();

        $faturamento = Faturamento::getFaturamentoAberto();
        $this->processaFaturamento($faturamento);

        echo "\n" . date('Y-m-d H:i:s');;
    }

    private function processaFaturamento(Faturamento $faturamento = null)
    {
        if (is_null($faturamento)) {
            return;
        }

        DB::delete("DELETE FROM tmp_faturamento_gordura WHERE faturamento = {$faturamento->id}");

        $dataInicioFaturamento = Carbon::create($faturamento->ano, $faturamento->mes, 1, 0, 0, 0);

        $sql = FaturamentoProcedimento::select(
            [
                DB::raw("DATE_FORMAT(agendas.data,'%Y-%m-%d') as data"),
                'faturamento_procedimentos.faturamento',
                'arenas.nome AS arena',
                'linha_cuidado.nome AS linha_cuidado',
                'procedimentos.sus AS procedimento_sus',
                'procedimentos.nome AS procedimento',
                'atendimento_procedimentos.faturado AS faturado',
                'profissionais.cro AS crm',
                'profissionais.nome AS medico',
                DB::raw("sum(atendimento_procedimentos.quantidade * atendimento_procedimentos.multiplicador) as quantidade")
            ]
        )
            ->join('atendimento_procedimentos', 'faturamento_procedimentos.atendimento_procedimento', '=', 'atendimento_procedimentos.id')
            ->join('atendimento', 'atendimento.id', '=', 'atendimento_procedimentos.atendimento')
            ->join('profissionais', 'atendimento.medico', '=', 'profissionais.id')
            ->join('procedimentos', 'procedimentos.id', '=', 'atendimento_procedimentos.procedimento')
            ->join('agendas', 'agendas.id', '=', 'atendimento.agenda')
            ->join('arenas', 'arenas.id', '=', 'agendas.arena')
            ->join('linha_cuidado', 'linha_cuidado.id', '=', 'agendas.linha_cuidado')
            ->where('faturamento_procedimentos.faturamento', $faturamento->id)
//            ->where('agendas.data', "<", $dataInicioFaturamento->toDateTimeString())
            ->groupBy(
                [
                    'faturamento_procedimentos.faturamento',
                    'arenas.id',
                    'linha_cuidado.id',
                    'procedimentos.id',
                    'profissionais.id',
                    'atendimento_procedimentos.faturado',
                    DB::raw("DATE_FORMAT(agendas.data,'%Y-%m-%d')"),
                ]
            )
        ;

        $bindings = $sql->getBindings();

        $insert = "INSERT INTO tmp_faturamento_gordura (data, faturamento, arena, linha_cuidado, procedimento_sus, procedimento, faturado, crm, medico, quantidade) " . $sql->toSql();
        DB::insert($insert, $bindings);
    }

    private function processaGordura()
    {
        try {
            $periodo['inicial'] = '2020-01-01 00:00:00';
            $periodo['final'] = Carbon::now();

            $sql = Atendimentos::select(
                [
                    DB::raw("DATE_FORMAT(agendas.data,'%Y-%m-%d') as data"),
                    'arenas.nome AS arena',
                    'linha_cuidado.nome AS linha_cuidado',
                    'procedimentos.sus AS procedimento_sus',
                    'procedimentos.nome AS procedimento',
                    'atendimento_procedimentos.faturado AS faturado',
                    'profissionais.cro AS crm',
                    'profissionais.nome AS medico',
                    DB::raw("sum(atendimento_procedimentos.quantidade * atendimento_procedimentos.multiplicador) as quantidade"),
                ]
            )
                ->join('profissionais', 'atendimento.medico', '=', 'profissionais.id')
                ->join('atendimento_procedimentos', 'atendimento.id', '=', 'atendimento_procedimentos.atendimento')
                ->join('procedimentos', 'procedimentos.id', '=', 'atendimento_procedimentos.procedimento')
                ->join('agendas', 'agendas.id', '=', 'atendimento.agenda')
                ->join('arenas', 'arenas.id', '=', 'agendas.arena')
                ->join('linha_cuidado', 'linha_cuidado.id', '=', 'agendas.linha_cuidado')
                ->whereIn('agendas.status', [6, 8, 98, 99])
                ->whereBetween('agendas.data', $periodo)
                ->where('atendimento_procedimentos.faturado', "=", 0)
                ->groupBy(
                    [
                        'arenas.id',
                        'linha_cuidado.id',
                        'procedimentos.id',
                        'profissionais.id',
                        'atendimento_procedimentos.faturado',
                        DB::raw("DATE_FORMAT(agendas.data,'%Y-%m-%d')"),
                    ]
                );

            $bindings = $sql->getBindings();

            $insert = "INSERT INTO tmp_faturamento_gordura (data, arena, linha_cuidado, procedimento_sus, procedimento, faturado, crm, medico, quantidade) " . $sql->toSql();
            DB::insert($insert, $bindings);

        } catch (\Exception $e) {
            exit("<pre>" . print_r($e->getMessage(), true) . "</pre>");
        }
    }


    private function createTable()
    {
        if (!Schema::hasTable('tmp_faturamento_gordura')) {
            Schema::create('tmp_faturamento_gordura', function (Blueprint $table) {
                $table->date('data')->index();
                $table->integer('faturamento')->index()->nullabe();
                $table->string('arena', 100);
                $table->string('linha_cuidado', 20);
                $table->string('procedimento_sus', 20);
                $table->string('procedimento', 100);
                $table->tinyInteger('faturado')->index();
                $table->string('crm');
                $table->string('medico');
                $table->integer('quantidade');
            });
        }
    }

    private function removeNaoFaturados()
    {
        DB::delete("DELETE FROM tmp_faturamento_gordura WHERE faturado = 0");
    }


}
