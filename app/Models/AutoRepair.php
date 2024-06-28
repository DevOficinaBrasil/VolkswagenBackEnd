<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutoRepair extends Model
{
    use HasFactory;

    protected $table = 'auto_repair';

    protected $fillable = [
        'CNPJ',
        'fantasy_name',
        'branch_activity',
        'auto_repair_phone',
        'auto_repair_address',
    ];
}
