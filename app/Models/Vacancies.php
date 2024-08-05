<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacancies extends Model
{
    use HasFactory;

    protected $table = "trainings_concessionaire";

    protected $fillable = [
        'vacancies',
    ];

    public function concessionaire()
    {
        return $this->belongsTo(Concessionaire::class, 'concessionaire_id');
    }

    public function trainings()
    {
        return $this->belongsTo(Training::class, 'training_id');
    }
}
