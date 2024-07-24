<?php

// app/Models/LandlordTransaction.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LandlordTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'landlord_id',
        'type',
        'amount',
        'tva_amount',
        'total_amount',
        'commission_amount',
        'net_amount',
        'transaction_date',
        'payout_date',
        'payment_method',
        'cheque_number',
        'cheque_amount',
        'cash_amount',
        'status',
        'month',
    ];

    public function landlord()
    {
        return $this->belongsTo(Landlord::class);
    }
}
