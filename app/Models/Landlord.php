<?php

// Landlord.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Landlord extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'address',
        'phone',
        'email',
        'identity_number',
        'identity_expiry_date',
        'agency_percentage',
        'contract_duration',
        'company_id',
        'attachments'
    ];

    protected $casts = [
        'attachments' => 'array',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }
}
