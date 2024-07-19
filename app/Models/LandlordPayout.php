<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LandlordPayout extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'landlord_id',
        'amount',
        'commission_amount',
        'net_amount',
        'description',
        'payout_date',
        'status',
    ];

    protected $dates = ['payout_date', 'deleted_at'];

    public function landlord()
    {
        return $this->belongsTo(Landlord::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $landlord = Landlord::find($model->landlord_id);
            $model->commission_amount = ($model->amount * $landlord->agency_percentage) / 100;
            $model->net_amount = $model->amount - $model->commission_amount;
            $model->payout_date = now();
            $model->status = 'Completed';
        });
    }
}
