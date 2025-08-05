<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AtendimentoCheckList extends Model{
    protected $table = 'atendimento_check_list';

    public static function checkPreferencial($atendimento){
        $atendimento = Atendimentos::find($atendimento);

        $agenda = Agendas::find($atendimento->agenda);
        $paciente = Pacientes::find($agenda->paciente);

        if($paciente->nascimento != null){
            $nascimento = explode("-",$paciente->nascimento);

            $idade = is_array($nascimento) ? \Carbon\Carbon::createFromDate($nascimento[0], $nascimento[1], $nascimento[2])->age : null;
            if($idade != null && $idade >= 60){
                $atendimento->preferencial = true;
                $atendimento->save();
            }
        }
    }
}
