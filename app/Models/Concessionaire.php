<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Concessionaire extends Model
{
    use HasFactory;

    protected $table = 'concessionaire';

    protected $fillable = [
        'CNPJ',
        'fantasy_name',
        'manager_name',
        'certify_name',
        'email',
        'phone',
        'DN',
        'concessionaire_login_id',
        'concessionaire_address',
    ];

    public function address()
    {
        return $this->belongsTo(Address::class, 'concessionaire_address');
    }

    public function trainings()
    {
        return $this->belongsToMany(Training::class, 'concessionaire_training_user', 'concessionaire_id', 'trainings_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'concessionaire_training_user', 'concessionaire_id', 'common_user_id');
    }

    public function trainingVacancies()
    {
        return $this->belongsToMany(Training::class, 'trainings_concessionaire', 'concessionaire_id', 'training_id')
            ->withPivot('vacancies');
    }
}
