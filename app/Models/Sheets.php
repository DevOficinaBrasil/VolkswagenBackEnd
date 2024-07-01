<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Sheets extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'general_sheet';

    protected $fillable = [
        'common_user_id',
        'trainings_id',
        'answers',
        'format',
    ];
}
