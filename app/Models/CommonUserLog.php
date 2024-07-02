<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommonUserLog extends Model
{
    use HasFactory;

    protected $table = 'common_user_log';

    protected $fillable = [
        'ID',
        'CadastroID',
        'Treinamento',
        'TreinamentoParticipou',
        'Participou',
        // 'DN',
        // 'Data'
    ];

    public $timestamps = true;
}