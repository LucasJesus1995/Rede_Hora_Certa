<?php

namespace App\Console\Commands\BI\GestaoAgenda;

use App\Http\Helpers\DateHelpers;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProducaoMetricaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cies:gestao-producao-metrica';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gestão Metrica Produção';
    protected $db_bi;
    protected $date;

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
        $this->db_bi = Schema::connection('datawarehouse');

        echo "Inicio: " . date('Y-m-d H:i:s');

        $this->date = Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-01 00:00:00"))->subMonth(3);

        $this->createTables();

        echo "\nTermino: " . date('Y-m-d H:i:s');
        echo "\n";
    }

    private function createTables()
    {
//        $this->mediaPlanejamento();
//        $this->unidades();
//        $this->especialidades();
//        $this->procedimentos();
        $this->producao();
        $this->agendamento();
//
//        $this->metaEspecialidade();
    }

    private function agendamentoTemp(){

        $sql = "";

        $this->db_bi->getConnection()->insert($sql);
    }


    private function metaEspecialidade()
    {
        $metas = $this->db_bi->getConnection()->select("SELECT * FROM ga_meta");
        $producoes = $this->db_bi->getConnection()->select("SELECT * FROM ga_producao WHERE ga_producao.data >= '{$this->date}' LIMIT 10;");

        $_data = [];
        $__data = [];
        if ($metas) {
            foreach ($metas AS $row) {
                $_data[$row->data]['meta'] = $row->meta;
            }
        }

        if ($producoes) {
            foreach ($producoes AS $producao) {
                var_dump(array_key_exists($producao->data, $_data));
                if (!array_key_exists($producao->data, $_data)) {
                    $__data[$producao->data]['meta'] = 0;
                } else {
                    $__data[$producao->data]['meta'] = $_data[$producao->data]['meta'];
                }
            }
        }


        exit("<pre>" . print_r($__data, true) . "</pre>");
    }

    private function procedimentos()
    {
        $this->db_bi->dropIfExists('ga_procedimentos');
        if (!$this->db_bi->hasTable('ga_procedimentos')) {
            $this->db_bi->create('ga_procedimentos', function (Blueprint $table) {
                $table->increments('id');
                $table->string('nome', 200);
                $table->string('sus', 12);
                $table->integer('forma_faturamnto');
                $table->integer('contador');
                $table->float('valor', 8, 2);
            });
        }

        $sql = "
                SELECT procedimentos.id,
                       procedimentos.nome,
                       procedimentos.sus AS codigo,
                       procedimentos.forma_faturamento,
                       procedimentos.contador,
                       (SELECT valor_unitario FROM sistema_ciesglobal_org.contrato_procedimentos  WHERE contrato = 2 AND lote  = 7 AND procedimento = procedimentos.id) AS valor_unitario
                FROM sistema_ciesglobal_org.procedimentos AS procedimentos
                ";

        $this->db_bi->getConnection()->insert("INSERT INTO ga_procedimentos {$sql}");
    }

    private function unidades()
    {
        $this->db_bi->dropIfExists('ga_unidades');
        if (!$this->db_bi->hasTable('ga_unidades')) {
            $this->db_bi->create('ga_unidades', function (Blueprint $table) {
                $table->increments('id');
                $table->string('nome', 200);
                $table->string('alias', 100);
            });
        }

        $sql = "
                SELECT arenas.id,
                       arenas.nome,
                       arenas.alias
                FROM sistema_ciesglobal_org.arenas
                ";

        $this->db_bi->getConnection()->insert("INSERT INTO ga_unidades {$sql}");
    }

    private function especialidades()
    {
        $this->db_bi->dropIfExists('ga_especialidades');
        if (!$this->db_bi->hasTable('ga_especialidades')) {
            $this->db_bi->create('ga_especialidades', function (Blueprint $table) {
                $table->increments('id');
                $table->string('nome', 200);
                $table->string('abreviacao', 20);
                $table->integer('tipo');
            });
        }

        $sql = "
                SELECT linha_cuidado.id,
                       linha_cuidado.nome,
                       linha_cuidado.abreviacao,
                       linha_cuidado.especialidade AS tipo
                FROM sistema_ciesglobal_org.linha_cuidado
                ";

        $this->db_bi->getConnection()->insert("INSERT INTO ga_especialidades {$sql}");
    }

    private function agendamento()
    {
        if (!$this->db_bi->hasTable('ga_agendamento')) {
            $this->db_bi->create('ga_agendamento', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('arena');
                $table->integer('especialidade');
                $table->date('data');
                $table->integer('quantidade')->default(1);
            });
        }

        $sql = "DELETE FROM ga_agendamento WHERE data >= {$this->date->toDateString()}";
        $this->db_bi->getConnection()->query($sql);

        $sql = "
                SELECT null,
                       agendas.arena                               AS arena,
                       agendas.linha_cuidado                       AS especialidade,
                       DATE_FORMAT(agendas.data, '%Y-%m-%d') AS data,
                       COUNT(agendas.id)                          AS quantidade
                FROM sistema_ciesglobal_org.agendas
                WHERE agendas.data >= '{$this->date}'
                GROUP BY arena, especialidade,  DATE_FORMAT(agendas.data, '%Y-%m-%d')
                ";

        $this->db_bi->getConnection()->insert("INSERT INTO ga_agendamento {$sql}");
    }

    private function producao()
    {
        if (!$this->db_bi->hasTable('ga_producao')) {
            $this->db_bi->create('ga_producao', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('arena');
                $table->integer('especialidade');
                $table->integer('procedimento');
                $table->date('data');
                $table->float('total_producao', 8, 2)->nullable();
            });
        }

        $sql = "DELETE FROM ga_producao WHERE data >= {$this->date->toDateString()}";
        $this->db_bi->getConnection()->query($sql);

        $sql = "
            SELECT null,   
                   agendas.arena,
                   agendas.linha_cuidado AS especialidade,
                   ap.procedimento,
                   DATE_FORMAT(agendas.data, '%Y-%m-%d')    AS data,
                    SUM(
                          ap.quantidade * ap.multiplicador * 
                          (SELECT contrato_procedimentos.valor_unitario FROM sistema_ciesglobal_org.contrato_procedimentos AS contrato_procedimentos  WHERE contrato_procedimentos.contrato = 2 AND contrato_procedimentos.lote  = 7 AND contrato_procedimentos.procedimento = ap.procedimento)
                      
                      ) AS total_producao
            FROM sistema_ciesglobal_org.agendas
                   JOIN sistema_ciesglobal_org.atendimento a on agendas.id = a.agenda
                   JOIN sistema_ciesglobal_org.atendimento_procedimentos ap on a.id = ap.atendimento
            WHERE agendas.data >= '{$this->date}'
                  GROUP BY arena, especialidade, procedimento, DATE_FORMAT(agendas.data, '%Y-%m-%d')
        ";

        $this->db_bi->getConnection()->insert("INSERT INTO ga_producao {$sql}");
    }

    private function mediaPlanejamento()
    {
        $teto = 6500000;

        $data = [];

        for ($mes = 2; $mes <= 2; $mes++) {
            $date = Carbon::create(date('Y'), $mes, '01', '0', '0', '0');
            $start = $date->startOfDay()->toDateString();
            $end = $date->lastOfMonth()->toDateString();;

            $data[$mes]['uteis'] = DateHelpers::getDiasUteis($start, $end);
            $data[$mes]['sabados'] = DateHelpers::getSabados($start, $end);
            $data[$mes]['calculo'] = floor(($data[$mes]['uteis'] + ($data[$mes]['sabados'] / 2)));

            $media = $teto / $data[$mes]['calculo'];

            for ($dia = 1; $dia <= $date->format('t'); $dia++) {
                $_dia = Carbon::create(date('Y'), $mes, $dia, '0', '0', '0');

                if ($_dia->dayOfWeek == 0) {
                    continue;
                }

                if ($_dia->dayOfWeek == 6) {
                    $data[$mes]['meta'][$_dia->toDateString()] = $media / 2;
                    continue;
                }

                $data[$mes]['meta'][$_dia->toDateString()] = $media;
            }
        }

        if (!$this->db_bi->hasTable('ga_meta')) {
            $this->db_bi->create('ga_meta', function (Blueprint $table) {
                $table->increments('id');
                $table->date('data')->unique();
                $table->float('meta', 8, 2);
            });
        }

        $sql = "DELETE FROM ga_meta WHERE data >= '" . date("Y-01-01") . "'";
        $this->db_bi->getConnection()->delete($sql);

        if (!empty($data)) {
            foreach ($data AS $rows) {
                foreach ($rows['meta'] AS $date => $meta) {
                    $sql = "INSERT INTO ga_meta (data, meta) VALUES ('{$date}', '{$meta}')";

                    $this->db_bi->getConnection()->insert($sql);
                }
            }
        }
    }


}
