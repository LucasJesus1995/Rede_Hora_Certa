<?php

namespace App;

use App\Http\Helpers\Util;
use Illuminate\Database\Eloquent\Model;

class AtendimentoStatus extends Model
{
    protected $table = 'atendimento_status';

    public static function setStatus($atendimento, $status,  $descricao = null)
    {
        $user = !empty(Util::getUser()) ?  Util::getUser() : null;

        $atendimento_status = new AtendimentoStatus();
        $atendimento_status->atendimento = $atendimento;
        $atendimento_status->user = $user;
        $atendimento_status->status = $status;
        $atendimento_status->observacao = $descricao;
        $atendimento_status->save();
    }

}
