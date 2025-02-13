<?php

namespace App\Models;

use App\Traits\Models\HasActivation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Admin extends Authenticatable implements JWTSubject
{
    use HasActivation;
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = ['name', 'email', 'password', 'is_active'];

    /**
     * @var string[]
     */
    protected $hidden = ['password'];

    public function setPasswordAttribute($value): void
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * @return mixed[]
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function isActiveAccount(): bool
    {
        return $this->is_active;
    }
}
