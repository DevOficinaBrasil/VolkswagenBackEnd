<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    use HasFactory;

    protected $table = 'trainings';

    protected $fillable = [
        'name',
        'live_url',
        'material',
        'active',
        'certify',
        'date',
        'description',
        'on_live',
        'year',
        'accomplished',
        'training_order'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'concessionaire_training_user', 'trainings_id', 'common_user_id')->withPivot(['id', 'concessionaire_id']);
    }

    public function concessionaire()
    {
        return $this->belongsToMany(Concessionaire::class, 'concessionaire_training_user', 'trainings_id', 'concessionaire_id');
    }

    public function concessionaires()
    {
        return $this->belongsToMany(Concessionaire::class, 'trainings_concessionaire');
    }
}
