<?php

namespace App\Http\Controllers\API;

use App\Atendimentos;
use App\Http\Requests\API\LoginRequest;
use App\Http\Transformers\AtendimentosDetalhesTransformers;
use App\Http\Transformers\AtendimentosTransformers;
use App\Http\Transformers\LoginPacienteTransformer;
use App\Pacientes;
use App\PacientesMobile;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AtendimentosController extends BaseController
{
    public function getAtendimentos(){

        $paciente = session('paciente');

        $atendimentos = Atendimentos::select('atendimento.*')
            ->join('agendas','agendas.id','=','atendimento.agenda')
            ->where('agendas.paciente', $paciente)
            ->whereIn('atendimento.status', [2,6,10,98,99])
            ->orderBy('atendimento.id','desc')
            ->paginate(10);


        return $this->paginator($atendimentos, new AtendimentosTransformers);

    }

    public function getAtendimentosDetalhes($atendimento){
        $paciente = session('paciente');

        $_atendimentos = Atendimentos::select('atendimento.*')
            ->join('agendas','agendas.id','=','atendimento.agenda')
            ->where('agendas.paciente', $paciente)
            ->where('atendimento.id', $atendimento)
            ->get();

        if(!count($_atendimentos))
            throw new NotFoundHttpException("Nenhum atendimento encontrado");

        return $this->item($_atendimentos[0], new AtendimentosDetalhesTransformers);
    }

}
