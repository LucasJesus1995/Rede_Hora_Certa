<?php

namespace App\Console\Commands;

use App\Agendas;
use App\Pacientes;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PacienteClearCommand extends Command
{

    protected $signature = 'cies:paciente-clear';

    protected $description = 'Limpar os paciente duplicado do sistema';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->clearPacienteDuplicadosByCNS();
    }

    private function clearPacienteDuplicadosByCNS()
    {
        $sql = "SELECT DISTINCT b.id, b.cns, b.import, b.created_at, b.updated_at FROM pacientes AS a, pacientes AS b WHERE a.cns = b.cns AND a.id < b.id AND a.cns != '' ORDER BY b.id DESC LIMIT 100";
        $data = DB::select($sql);

        foreach ($data AS $row) {

            DB::transaction(function () use ($row) {

                try {
                    if (!empty(trim($row->cns))) {
                        $pacientes = Pacientes::select('cns', 'id')->where('cns', $row->cns)->get();

                        if (!empty($pacientes[0])) {
                            foreach ($pacientes AS $paciente) {
                                if ($paciente->id != $row->id) {
                                    $agendas = Agendas::where('paciente', $paciente->id)->get();

                                    if(!empty($agendas[0])){
                                        Agendas::where('paciente', $paciente->id)->update(['paciente' => $row->id]);
                                    }

                                    $paciente->delete();
                                }
                            }
                        }
                    }

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();

                    print("<pre>Exception: " . print_r($e->getFile(), true) . "</pre>");
                    print("<pre>Exception: " . print_r($e->getLine(), true) . "</pre>");
                    exit("<pre>Exception: " . print_r($e->getMessage(), true) . "</pre>");
                }

            });
        }

    }

}


