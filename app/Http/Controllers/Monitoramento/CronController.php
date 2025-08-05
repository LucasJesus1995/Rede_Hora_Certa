<?php

namespace App\Http\Controllers\Monitoramento;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Monitoramento\CronHelpers;
use Illuminate\Support\Facades\Artisan;

class CronController extends Controller
{

    public function __construct()
    {
        $this->title = "CRON (Rotinas";

        parent::__construct();
    }

    public function getIndex()
    {
        $view = View("admin.monitoramento.cron.index");

        $view->jobs = CronHelpers::getCronForceRun();

        return $view;
    }

    public function getServices($servico)
    {
        $cron = CronHelpers::getCronForceRun($servico);

        if (!empty($cron) && !empty($cron['service'])) {
            $command = "php " . public_path() . "/../artisan {$cron['service']}  > /dev/null 2>&1 &";
            exec($command);
        }
    }

}
