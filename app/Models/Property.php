<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'address',
        'price',
        'company_id',
        'designation',
        'number',
        'rental_price',
        'sale_price',
        'landlord_id',
    ];

    protected $casts = [
        'rental_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function landlord()
    {
        return $this->belongsTo(Landlord::class);
    }
}
