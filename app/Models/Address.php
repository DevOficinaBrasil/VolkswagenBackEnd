<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $table = 'address';

    protected $fillable = [
        'street',
        'number',
        'complement',
        'cep',
        'city_id',
    ];

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }
}
