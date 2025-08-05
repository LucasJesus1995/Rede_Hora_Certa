<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DataWarehouseSync extends Command
{
    protected $signature = 'cies:sync-datawarehouse';

    protected $description = 'Sincroniza os banco de dados datawarehouse com o produção';

    private $atualizacao = null;
    private $atualizacao_new = null;

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        echo "Inicio: " . date('Y-m-d H:i:s');

        $this->atualizacao_new = date('Y-m-d H:i:s');
        $this->db_bi = Schema::connection('datawarehouse');

        $this->sourceSQL();

        $this->t_atualizacao();

        echo "\nTermino: " . date('Y-m-d H:i:s');
        echo "\n";
    }

    private function sourceSQL()
    {
        $file = app_path("Console/Commands/BI/sql/bi_sistema_ciesglobal_org.sql");

        if (file_exists($file)) {
            $comando = "mysql -h " . env('DB_HOST') . " -u" . env('DB_USERNAME') . " -p" . env('DB_PASSWORD') . " bi_sistema_ciesglobal_org < " . $file . " &>/dev/null";
            exec($comando);
        }
    }

    private function t_atualizacao()
    {
        $this->db_bi->dropIfExists('bi_atualizacao');
        if (!$this->db_bi->hasTable('bi_atualizacao')) {
            $this->db_bi->create('bi_atualizacao', function (Blueprint $table) {
                $table->dateTime('atualizacao');
            });

            $this->db_bi->getConnection()->insert("INSERT INTO bi_atualizacao (`atualizacao`) VALUES ('{$this->atualizacao_new}')");
        }
    }


}