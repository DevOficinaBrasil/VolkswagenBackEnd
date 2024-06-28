<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingUser extends Model
{
    use HasFactory;

    protected $table = 'concessionaire_training_user';

    protected $fillable = [
        'concessionaire_id',
        'common_user_id',
        'trainings_id',
        'presence',
    ];
}
