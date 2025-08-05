<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContatoPaciente extends Model
{
    protected $table = 'contato_pacientes';

    protected $fillable = ['paciente', 'agenda', 'user', 'status', 'descricao'];
}
