<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    use HasFactory;

    protected $table = 'common_user_log';

    protected $fillable = [
        'CadastroID',
        'Treinamento',
        'TreinamentoParticipou',
        'Participou',
        'concessionaire_id',
    ];
}
