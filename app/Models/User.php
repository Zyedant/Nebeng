<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'email',
        'password',
        'name',
        'phone_number',
        'gender',
        'birth_date',
        'birth_place',
        'image',
        'is_banned',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'is_banned' => 'boolean',
    ];

    // Relationships
    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    public function partner()
    {
        return $this->hasOne(Partner::class);
    }

    public function partnerPosts()
    {
        return $this->hasMany(PartnerPost::class);
    }

    // Helper methods
    public function isSuperadmin()
    {
        return $this->role === 'Superadmin';
    }

    public function isAdmin()
    {
        return $this->role === 'Admin';
    }

    public function isFinance()
    {
        return $this->role === 'Finance';
    }

    public function isMitra()
    {
        return $this->role === 'Mitra';
    }

    public function isPosMitra()
    {
        return $this->role === 'Pos Mitra';
    }

    public function isCustomer()
    {
        return $this->role === 'Customer';
    }

    public function getAllowedRolesForDashboard()
    {
        return ['Superadmin', 'Admin', 'Finance'];
    }

    public function canAccessDashboard()
    {
        return in_array($this->role, $this->getAllowedRolesForDashboard());
    }
}