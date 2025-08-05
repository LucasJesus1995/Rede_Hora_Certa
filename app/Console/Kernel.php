<?php

namespace App\Console;

use App\Console\Commands\AgendasStatusFinalizadoCommand;
use App\Console\Commands\AtendimentoFaltaCommands;
use App\Console\Commands\AtendimentoProcedimentosCommand;
use App\Console\Commands\AtendimentosNaoAtendidoCommand;
use App\Console\Commands\Backup;
use App\Console\Commands\BI\GestaoAgenda\ProducaoMetricaCommand;
use App\Console\Commands\BI\StreamPBICommand;
use App\Console\Commands\ClearDatabase;
use App\Console\Commands\DataWarehouseSync;
use App\Console\Commands\Faturamento\AjusteStatusProcedimentoAgendaCommand;
use App\Console\Commands\Faturamento\RelatorioGorduraCommand;
use App\Console\Commands\FaturamentoCommand;
use App\Console\Commands\FaturamentoCorrecoesCommand;
use App\Console\Commands\FaturamentoProcedimentosCommand;
use App\Console\Commands\FaturamentoProcedimentosConsolidadoCommand;
use App\Console\Commands\FaturamentoProcedimentosMetasCommand;
use App\Console\Commands\FaturamentoProcedimentosObrigatoriosCommand;
use App\Console\Commands\Importacao\ImportacaoAgendasTempCommand;
use App\Console\Commands\PacienteClearCommand;
use App\Console\Commands\PacientesCommand;
use App\Console\Commands\RestoreFaturamentoCommand;
use App\Console\Commands\SQL\Temp\PacientesFaltaTempCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    protected $commands = [
        FaturamentoCommand::class,
        Backup::class,
        AtendimentoFaltaCommands::class,
        ClearDatabase::class,
        FaturamentoProcedimentosCommand::class,
        FaturamentoProcedimentosObrigatoriosCommand::class,
        FaturamentoProcedimentosConsolidadoCommand::class,
        AtendimentoProcedimentosCommand::class,
        DataWarehouseSync::class,
        RestoreFaturamentoCommand::class,
        FaturamentoCorrecoesCommand::class,
        PacienteClearCommand::class,
        PacientesCommand::class,
        StreamPBICommand::class,
        AtendimentosNaoAtendidoCommand::class,
        ProducaoMetricaCommand::class,
        AgendasStatusFinalizadoCommand::class,
        FaturamentoProcedimentosMetasCommand::class,
        RelatorioGorduraCommand::class,
        AjusteStatusProcedimentoAgendaCommand::class,
        ImportacaoAgendasTempCommand::class
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('cies:atendimento-nao-atendido')->daily()->at('00:15');

        $schedule->command('cies:importacao-agendas-temp')->daily()->at('23:55');

        $schedule->command('cies:atendimento-falta')->daily()->at('00:30');

        $schedule->command('cies:backup-db')->daily()->at('01:30');
//        $schedule->command('cies:cies:sync-datawarehouse')->daily()->at('04:00');

        $schedule->command('cies:paciente-clear')->daily()->at('02:30');
        $schedule->command('cies:paciente-clear')->daily()->at('04:35');

        $schedule->command('cies:faturamento-status-procedimento-agenda')->daily()->at('02:15');

        $schedule->command('cies:clear-db')->daily()->at('06:00');

        $schedule->command('cies:faturamento-procedimentos-consolidado')->daily()->at('05:40');
        $schedule->command('cies:faturamento-procedimentos-consolidado')->daily()->at('12:45');

        $schedule->command('cies:faturamento-procedimentos-obrigatorios')->daily()->at('05:10');
        $schedule->command('cies:faturamento-procedimentos-obrigatorios')->daily()->at('12:15');

        $schedule->command('cies:pacientes')->daily()->at('22:30');
        $schedule->command('cies:pacientes')->daily()->at('00:01');
        $schedule->command('cies:pacientes')->daily()->at('03:00');
        $schedule->command('cies:pacientes')->daily()->at('05:00');

        $schedule->command('cies:faturamento-procedimentos-metas')->daily()->at('08:20');
        $schedule->command('cies:faturamento-procedimentos-metas')->daily()->at('10:20');
        $schedule->command('cies:faturamento-procedimentos-metas')->daily()->at('15:20');
        $schedule->command('cies:faturamento-procedimentos-metas')->daily()->at('21:30');

        $schedule->command('cies:faturamento')->daily()->everyThirtyMinutes();

        $schedule->command('cies:relatorio-gordura')->daily()->at('20:30');

        $schedule->command('cies:agendas-status')->daily()->at('03:00');
        $schedule->command('cies:agendas-status')->daily()->at('12:11');
    }

}
