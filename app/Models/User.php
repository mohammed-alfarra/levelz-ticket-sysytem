<?php

namespace App\Models;

use App\Scopes\OrderByCreatedAt;
use App\Traits\Models\HasActivation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasActivation;
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'email_verified_at',
        'password',
        'is_active',
        'device_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::addGlobalScope(new OrderByCreatedAt);
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

    public function setPasswordAttribute($value): void
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function scopeRecentRegistered(Builder $query, int $duration): int
    {
        return $query->where('created_at', '>', now()->subDays($duration))
            ->count();
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name.' '.$this->last_name;
    }

    public function updateDevice($device_token): void
    {
        $this->update([
            'device_token' => $device_token,
        ]);
    }
}
