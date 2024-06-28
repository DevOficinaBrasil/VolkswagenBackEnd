<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'common_user';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'gender',
        'born_at',
        'document',
        'user_login_id',
        'common_user_address',
    ];
    
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function trainings()
    {
        return $this->belongsToMany(Training::class, 'concessionaire_training_user', 'common_user_id', 'trainings_id')
            ->withPivot('concessionaire_id', 'presence');
    }

    public function concessionaire()
    {
        return $this->belongsToMany(Concessionaire::class, 'concessionaire_training_user', 'common_user_id', 'concessionaire_id');
    }
}
