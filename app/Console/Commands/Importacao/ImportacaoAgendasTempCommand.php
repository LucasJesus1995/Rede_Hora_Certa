<?php

namespace App\Console\Commands\Importacao;

use App\Atendimentos;
use App\Http\Helpers\DateHelpers;
use App\Http\Helpers\Util;
use App\ImportacaoAgenda;
use App\TempImportacaoAgenda;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportacaoAgendasTempCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cies:importacao-agendas-temp {full=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';
    /**
     * @var array|string
     */
    private $full;

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
        $this->full = $this->argument('full');

        foreach ($this->getPeriodo() as $periodo) {
            $data = (new \App\Http\Helpers\Importacao\ImportacaoAgendasHelpers)->getAgendamentos(Util::DB2User($periodo));
            if (!empty($data)) {
                try {
                    $this->saveDate($periodo, $data);
                } catch (\Exception $e){

                }
            }
            echo ".";
        }
    }

    private function saveDate($date, array $data)
    {
        TempImportacaoAgenda::where('date_importacao', $date)->delete();
        TempImportacaoAgenda::whereIn('id', array_keys($data))->delete();
        $util = new Util();

        foreach ($data as $k => $item) {
            $data[$k]['data_importacao'] = !empty($item['data_importacao']) ? $util->Timestamp2DB($item['data_importacao']) : null;
            $data[$k]['data'] = !empty($item['data']) ? $util->Date2DB($item['data']) : null;
        }

        foreach (array_chunk($data, 300, true) as $itens) {
            TempImportacaoAgenda::insert($itens);
        }

        DB::statement("UPDATE temp_importacao_agenda SET date_importacao = DATE(data_importacao) WHERE id IN (" . implode(",", array_keys($data)) . ")");
    }

    private function createTable()
    {
        $sql = "DROP TABLE IF EXISTS temp_importacao_agenda;

                CREATE TABLE `temp_importacao_agenda` (
                  `id` int(11) NOT NULL,
                  `data_importacao` datetime DEFAULT NULL,
                  `date_importacao` date DEFAULT NULL,
                  `data` date DEFAULT NULL,
                  `user` varchar(200) NOT NULL,
                  `tipo` varchar(10) DEFAULT NULL,
                  `tipo_atendimento` varchar(200) DEFAULT NULL,
                  `registro` int(11) NOT NULL DEFAULT '0',
                  `importacao` int(11) NOT NULL DEFAULT '0',
                  `falhas` int(11) NOT NULL DEFAULT '0',
                  `arena` varchar(200) NOT NULL,
                  `linha_cuidado` varchar(200) NOT NULL,
                  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                
                ALTER TABLE `temp_importacao_agenda` ADD PRIMARY KEY (`id`),  ADD KEY `idx_date_importacao` (`date_importacao`);
                ALTER TABLE `temp_importacao_agenda` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
                ";

        DB::statement($sql);
    }

    private function getPeriodo()
    {
        if ($this->full == 1) {
//            $this->createTable();
            $periodo = ImportacaoAgenda::distinct()->selectRaw('DATE(created_at) as data')->orderBy('created_at', 'DESC')->lists('data')->toArray();
        } else {
            $periodo[] = date('Y-m-d');
//            $periodo[] = "2021-06-02";
        }

        return $periodo;
    }

}
