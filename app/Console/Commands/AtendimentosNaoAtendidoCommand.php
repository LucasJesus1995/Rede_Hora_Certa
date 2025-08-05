<?php

namespace App\Console\Commands;

use App\AgendaEtapas;
use App\Agendas;
use App\AtendimentoAcuidadeVisual;
use App\AtendimentoAlergias;
use App\AtendimentoAnamnenseRespostas;
use App\AtendimentoAtividadeFisica;
use App\AtendimentoAuxiliar;
use App\AtendimentoDietas;
use App\AtendimentoDoencaCardiovascular;
use App\AtendimentoDoencasCronica;
use App\AtendimentoExameIMC;
use App\AtendimentoExameIntralBucalMaxila;
use App\AtendimentoExameLaboratoriais;
use App\AtendimentoHistoricoFamiliar;
use App\AtendimentoMedicacaoRegularmente;
use App\AtendimentoMedicamentos;
use App\AtendimentoMichigan;
use App\AtendimentoProcedimentos;
use App\AtendimentoQueixas;
use App\Atendimentos;
use App\AtendimentoSexo;
use App\AtendimentoSinaisVitais;
use App\AtendimentoStatus;
use App\AtendimentoSubmeteuCirurgia;
use App\AtendimentoVacinacao;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AtendimentosNaoAtendidoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cies:atendimento-nao-atendido';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancela todo os atendimentos que não foram finalizado pelo digitador';

    protected $data_inicial = "2018-01-01 00:00:00";
    protected $limit = 500;

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
        $this->atendimentos();
    }

    private function atendimentos()
    {
        $atendimentos = $this->getAtendimentos();
        if (!empty($atendimentos[0])) {
            foreach ($atendimentos AS $atendimento) {

                DB::transaction(function () use ($atendimento) {

                    try {
                        AtendimentoProcedimentos::where('atendimento', $atendimento->id)->delete();
                        AtendimentoStatus::where('atendimento', $atendimento->id)->delete();
                        AtendimentoMedicamentos::where('atendimento', $atendimento->id)->delete();
                        AtendimentoAnamnenseRespostas::where('atendimento', $atendimento->id)->delete();
                        AgendaEtapas::where('atendimento', $atendimento->id)->delete();
                        AtendimentoAlergias::where('atendimento', $atendimento->id)->delete();
                        AtendimentoAtividadeFisica::where('atendimento', $atendimento->id)->delete();
                        AtendimentoSinaisVitais::where('atendimento', $atendimento->id)->delete();
                        AtendimentoExameIMC::where('atendimento', $atendimento->id)->delete();
                        AtendimentoExameLaboratoriais::where('atendimento', $atendimento->id)->delete();
                        AtendimentoSexo::where('atendimento', $atendimento->id)->delete();
                        AtendimentoAcuidadeVisual::where('atendimento', $atendimento->id)->delete();
                        AtendimentoAuxiliar::where('atendimento', $atendimento->id)->delete();
                        AtendimentoDietas::where('atendimento', $atendimento->id)->delete();
                        AtendimentoDoencaCardiovascular::where('atendimento', $atendimento->id)->delete();
                        AtendimentoDoencasCronica::where('atendimento', $atendimento->id)->delete();
                        AtendimentoExameIntralBucalMaxila::where('atendimento', $atendimento->id)->delete();
                        AtendimentoHistoricoFamiliar::where('atendimento', $atendimento->id)->delete();
                        AtendimentoMedicacaoRegularmente::where('atendimento', $atendimento->id)->delete();
                        AtendimentoMichigan::where('atendimento', $atendimento->id)->delete();
                        AtendimentoQueixas::where('atendimento', $atendimento->id)->delete();
                        AtendimentoSubmeteuCirurgia::where('atendimento', $atendimento->id)->delete();
                        AtendimentoVacinacao::where('atendimento', $atendimento->id)->delete();
                        
                        $atendimento->delete();

                        $agenda = Agendas::find($atendimento->agenda);
                        $agenda->status = 1;
                        $agenda->save();

                        DB::commit();

                        echo "\n{$agenda->id}";
                    } catch (\Exception $e) {
                        DB::rollBack();

                        throw new \Exception($e->getMessage());
                    }
                });
            }
        }
    }

    private function getAtendimentos()
    {
        $_date_final = Carbon::createFromDate();
        $_date_final->setTime(23, 59, 59);
        $_date_final->subDay(7);

        $date = [
            $this->data_inicial,
            $_date_final->toDateTimeString()
        ];

        $atendimentos = Atendimentos::select(
            [
                'atendimento.*'
            ]
        )
            ->whereBetween('agendas.data', $date)
            ->join('agendas', 'atendimento.agenda', '=', 'agendas.id')
            ->where('agendas.status', 2)
            ->whewhereRawreRaw("atendimento.id NOT IN (SELECT atendimento_laudo.atendimento FROM  atendimento_laudo WHERE atendimento_laudo.atendimento = atendimento.id)")
            ->orderBy('agendas.id', 'desc')
            ->limit($this->limit)
            ->get();

        return $atendimentos;
    }
}


