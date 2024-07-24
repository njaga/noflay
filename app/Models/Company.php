<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'website',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function landlords()
    {
        return $this->hasMany(Landlord::class);
    }

    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }

    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    public function isActive()
    {
        return $this->is_active;
    }

    public function contracts(){
        return $this->hasMany(Contract::class);
    }
}
