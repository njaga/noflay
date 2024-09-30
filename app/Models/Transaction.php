<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'payment_id',
        'date',
        'amount',
        'type',
        'description',
        'property_id',
        'landlord_id',
        'tenant_id',
        'contract_id',
        'expense_id',
        'refund_reason',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    // Relations
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function landlord()
    {
        return $this->belongsTo(Landlord::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }

    public const REFUND_REASONS = [
        'caution' => 'Remboursement de caution',
        'overpayment' => 'Trop-perçu de loyer',
        'charges' => 'Régularisation des charges',
        'repairs' => 'Remboursement de frais de réparations',
        'other' => 'Autre remboursement',
    ];
}
