<?php

namespace Modules\User\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Modules\Order\Models\Order;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasApiTokens, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function getDefaultGuardName(): string
    {
        return 'api';
    }

    /**
     * Get the orders belongs to the user.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }


    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }
}
