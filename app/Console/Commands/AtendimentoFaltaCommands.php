<?php

namespace App\Console\Commands;

use App\LinhaCuidado;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AtendimentoFaltaCommands extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cies:atendimento-falta';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Coloca todos os atendimento como falta';

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
        $now = Carbon::now()->subDay(1)->toDateTimeString();

        DB::table('agendas')
            ->whereIn('status', [0,1, 5])
            ->where("data", '<', $now)
            ->update(['status' => 7]);
    }
}
