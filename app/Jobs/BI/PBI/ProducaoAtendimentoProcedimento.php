<?php

namespace App\Jobs\BI\PBI;

use App\Agendas;
use App\AtendimentoProcedimentos;
use App\ContratoProcedimentos;
use App\Jobs\Job;
use App\Services\BI\MonitorAtendimentoProcedimentos;
use Carbon\Carbon;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Schema;

class ProducaoAtendimentoProcedimento extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $atendimentoProcedimento;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(AtendimentoProcedimentos $atendimentoProcedimento)
    {
        $this->atendimentoProcedimento = $atendimentoProcedimento;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        try {
            $data = null;

            $agenda = Agendas::select(
                [
                    'agendas.id',
                    'arenas.nome AS arena',
                    'linha_cuidado.nome AS linha_cuidado',
                    'agendas.data',
                    'agendas.status',
                ]
            )
                ->join('atendimento', 'atendimento.agenda', '=', 'agendas.id')
                ->join('arenas', 'arenas.id', '=', 'agendas.arena')
                ->join('linha_cuidado', 'linha_cuidado.id', '=', 'agendas.linha_cuidado')
                ->where('atendimento.id', $this->atendimentoProcedimento->atendimento)
                ->get();

            $agenda = $agenda[0];

            $date = Carbon::createFromFormat('Y-m-d H:i:s', $agenda->data);
            $contrato_procedimento = ContratoProcedimentos::getContratoProcedimentoByContratoLote();

            $data['atendimento_procedimento'] = $this->atendimentoProcedimento->id;
            $data['atendimento'] = $this->atendimentoProcedimento->atendimento;
            $data['agenda'] = $agenda->id;
            $data['procedimento'] = $this->atendimentoProcedimento->procedimento;
            $data['arena'] = $agenda->arena;
            $data['linha_cuidado'] = $agenda->linha_cuidado;
            $data['data'] = $agenda->data;
            $data['faturado'] = $this->atendimentoProcedimento->faturado;
            $data['created_at'] = date('Y-m-d 00:00:00');
            $data['valor'] = array_key_exists($this->atendimentoProcedimento->procedimento, $contrato_procedimento) ? $contrato_procedimento[$this->atendimentoProcedimento->procedimento] : null;
            $data['status'] = $agenda->status;
            $data['dia'] = $date->format('d');
            $data['data_mes'] = $date->format('Y-m-d');
            $data['quantidade'] = ($this->atendimentoProcedimento->multiplicador * $this->atendimentoProcedimento->quantidade);
            $data['total'] = $data['quantidade'] * $data['valor'];

            $db = Schema::connection('datawarehouse');

            if (!$db->hasTable("monitor_atendimento_procedimentos")) {
                $db->create("monitor_atendimento_procedimentos", function (Blueprint $table) {
                    $table->increments('id');
                    $table->integer('atendimento_procedimento');
                    $table->integer('atendimento');
                    $table->integer('agenda');
                    $table->integer('procedimento');
                    $table->string('arena');
                    $table->string('linha_cuidado');
                    $table->integer('quantidade')->default(1);
                    $table->float('valor', 8, 2)->nullable();
                    $table->float('total', 8, 2)->nullable();
                    $table->boolean('faturado');
                    $table->integer('status');
                    $table->datetime('data');
                    $table->date('data_mes');
                    $table->integer('dia');
                    $table->date('created_at');
                    $table->boolean('processado')->default(0);
                });
            }

//            $monitor_atendimento_procedimento = $db->getConnection()->select("SELECT * FROM monitor_atendimento_procedimentos WHERE atendimento = {$data['atendimento']} AND procedimento = {$data['procedimento']} ");

//            if (!empty($monitor_atendimento_procedimento[0])) {
//                $data['id'] = $monitor_atendimento_procedimento[0]->id;
//                $db->getConnection()->table('monitor_atendimento_procedimentos')->update($data);
//            } else {
                $db->getConnection()->table('monitor_atendimento_procedimentos')->insert($data);
//            }
        } catch (\Exception $e) {
            exit("<pre>" . print_r($e->getMessage(), true) . "</pre>");
        }
    }
}
