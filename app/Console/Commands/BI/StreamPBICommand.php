<?php

namespace App\Console\Commands\BI;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class StreamPBICommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pbi:stream';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';
    private $_db_bi;

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
        try {
            $this->_db_bi = Schema::connection('datawarehouse');

            $this->removeDuplicados();

            $this->sendPBIAtendimentoProcedimento();
        } catch (\Exception $exception) {
            print("<pre>" . print_r($exception->getFile(), true) . "</pre>");
            print("<pre>" . print_r($exception->getLine(), true) . "</pre>");
            exit("<pre>" . print_r($exception->getMessage(), true) . "</pre>");
        }

        $this->_db_bi->getConnection()->disconnect();

    }

    private function sendPBIAtendimentoProcedimento($limit = 100)
    {
        $url = env('PBI_API_ATENDIMENTO_PROCEDIMENTO');
        $data = null;
        $ids = null;

        $sql = "SELECT id, atendimento_procedimento, atendimento, agenda, procedimento, arena, linha_cuidado, quantidade, valor, total, faturado, status, data, data_mes, dia FROM monitor_atendimento_procedimentos WHERE processado = 0 ORDER BY id DESC LIMIT {$limit}";
        $rows = $this->_db_bi->getConnection()->select($sql);

        if (!empty($rows[0])) {
            foreach ($rows AS $row) {
                $id = $row->id;
                unset($row->id);

                if (is_null($row->total)) {
                    $row->total = $row->quantidade * $row->valor;
                }

                $data[] = (array)$row;
                $ids[] = $id;
            }
        }

        if (!empty($data[0])) {
            $response = $this->sendRequest($url, $data);

            if ($response) {
                $this->_db_bi->getConnection()->update("UPDATE monitor_atendimento_procedimentos SET processado = 1 WHERE id IN (" . implode(",", $ids) . ")");
            }
        }
    }

    private function removeDuplicados()
    {
        $this->_db_bi->getConnection()->delete("DELETE a FROM monitor_atendimento_procedimentos a, monitor_atendimento_procedimentos b WHERE a.atendimento_procedimento = b.atendimento_procedimento  AND a.id < b.id;");
    }

    private function sendRequest($url, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $output = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return ($httpcode >= 200 && $httpcode < 300);
    }


}
