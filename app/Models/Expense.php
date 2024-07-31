<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'property_id',
        'type',
        'description',
        'amount',
        'expense_date',
    ];

    const VALID_TYPES = ['maintenance', 'frais judiciaires', 'taxes', 'Assurance', 'other'];


    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
