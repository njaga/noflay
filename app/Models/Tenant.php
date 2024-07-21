<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'address',
        'id_card_number',
        'id_card_expiration_date',
        'company_id',
        'property_id',
    ];

    public function rentalApplications()
    {
        return $this->hasMany(RentalApplication::class);
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function properties()
    {
        return $this->hasManyThrough(
            Property::class,
            RentalApplication::class,
            Contract::class,
            'tenant_id', // Foreign key on RentalApplication table
            'id', // Foreign key on Property table
            'id', // Local key on Tenant table
            'property_id' // Local key on RentalApplication table
        );
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function landlord()
    {
        return $this->belongsTo(Landlord::class);
    }

}
