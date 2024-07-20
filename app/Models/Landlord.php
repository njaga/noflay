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
        'balance',
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

    public function totalCommissions()
    {
        $totalCommission = 0;
        foreach ($this->properties as $property) {
            foreach ($property->contracts as $contract) {
                $totalCommission += $contract->rent * ($this->agency_percentage / 100);
            }
        }
        return $totalCommission;
    }

    public function calculateBalance()
    {
        $totalRentIncome = 0;
        $totalExpenses = 0;
        $totalCommissions = 0;

        foreach ($this->properties as $property) {
            foreach ($property->contracts as $contract) {
                $totalRentIncome += $contract->payments->sum('amount');
                $totalCommissions += $contract->commission_amount;
            }
            $totalExpenses += $property->expenses->sum('amount');
        }

        return $totalRentIncome - $totalExpenses - $totalCommissions;
    }

    public function transactions()
    {
        return $this->hasMany(LandlordTransaction::class);
    }

    public function payouts()
    {
        return $this->hasMany(LandlordPayout::class);
    }

}
