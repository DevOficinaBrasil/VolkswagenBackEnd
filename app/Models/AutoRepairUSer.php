<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutoRepairUSer extends Model
{
    use HasFactory;
    
    protected $table = 'auto_repair_user';

    protected $fillable = [
        'CNPJ',
        'common_user',
        'auto_repair_id',
        'role',
    ];
}
