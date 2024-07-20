<?php

// Contract.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'property_id',
        'landlord_id',
        'contract_type',
        'start_date',
        'end_date',
        'file_number',
        'caution_amount',
        'is_reversed',
        'rent_amount',
        'commission_amount',
        'company',
        'representative',
        'trade_register',
        'caution_amount',
        'company_id',
    ];

    protected $dates = [
        'start_date',
        'end_date',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function landlord()
    {
        return $this->belongsTo(Landlord::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
