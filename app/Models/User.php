<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'department',
        'employee_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function uploadedBoms()
    {
        return $this->hasMany(BomHeader::class, 'uploaded_by');
    }

    public function raisedIntents()
    {
        return $this->hasMany(PurchaseIntent::class, 'raised_by');
    }

    public function isPurchaseDept()
    {
        return $this->hasRole('purchase-dept') || $this->hasRole('admin');
    }

    public function isStoreManager()
    {
        return $this->hasRole('store-manager') || $this->hasRole('admin');
    }

    public function isEngineer()
    {
        return $this->hasRole('engineer') || $this->hasRole('admin');
    }
}