<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Helpers\Util;

class Atestado extends Model
{
    protected $table = 'atestado';

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            foreach ($model->getAttributes() AS $key => $value) {
                $model->$key = Util::String2DB($value);
            }
        });
    }

    public static function searchAtestado($id)
    {
        $sql = self::select(
            [
                'atestado.id',
                'atestado.atendimento',
                'atestado.cid',
                'cid.codigo as codigoCid',
                'cid.descricao as descricaoCid',
                'atestado.empresa',
                'atestado.hora_chegada',
                'atestado.hora_saida',
                'atestado.tempo_repouso',
                'atendimento.medico',
                'profissionais.nome as nomeMedico',
                'profissionais.cro as crmMedico',
                'atendimento.agenda',
                'agendas.paciente',
                'pacientes.nome as pacienteNome',
                'pacientes.cpf',
                'pacientes.sexo',
                'pacientes.mae as pacienteMae',
                'pacientes.nascimento as pacienteNascimento',
                'agendas.arena',
                'arenas.nome as arenaNome',
                'agendas.data',
                'agendas.linha_cuidado',
                'linha_cuidado.nome as nomeLinhaCuidado',
            ]
        )
        ->join('cid', 'atestado.cid','=','cid.id' )
        ->join('atendimento', 'atestado.atendimento','=','atendimento.id' )
        ->join('agendas', 'atendimento.agenda','=','agendas.id' )
        ->join('profissionais', 'atendimento.medico','=','profissionais.id' )
        ->join('pacientes', 'agendas.paciente','=','pacientes.id' )
        ->join('arenas', 'agendas.arena','=','arenas.id' )
        ->join('linha_cuidado', 'agendas.linha_cuidado','=','linha_cuidado.id' )
        ->where('atendimento.id', $id)
        ->get()->toArray();

        return $sql;
    }
}
