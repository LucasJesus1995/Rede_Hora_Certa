<?php

namespace App\Http\Rules\Agendas;


use App\Agendas;
use App\Pacientes;

class NovoRegistroValidate
{

    protected $agenda;

    public function __construct(Agendas $agenda)
    {
        $this->agenda = $agenda;
    }

    public function process()
    {
        $this->agendaHorario();
    }

    private function agendaHorario()
    {
        $data = Agendas::where('paciente', $this->agenda->paciente)->where('data', '=', $this->agenda->data)->get();

        if (!empty($data[0])) {
            throw new \Exception("Paciente já esta cadastrando nesta agenda");
        }
    }

}