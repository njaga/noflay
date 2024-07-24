<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'contract_id',
        'tenant_id',
        'amount',
        'payment_date',
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function property()
    {
        return $this->contract->property();
    }
}
