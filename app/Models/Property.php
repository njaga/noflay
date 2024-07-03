<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'landlord_id',
        'property_type',
        'name',
        'description',
        'price',
        'address',
        'available_count',
        'company_id',
        'photos',
    ];

    protected $casts = [
        'photos' => 'array',
    ];

    public function landlord()
    {
        return $this->belongsTo(Landlord::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function rentalApplications()
    {
        return $this->hasMany(RentalApplication::class);
    }

    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }
}
