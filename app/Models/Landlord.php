<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Landlord extends Model
{
    use HasFactory;

    protected $fillable = [
        'prenom',
        'nom',
        'telephone',
        'email',
        'adresse',
        'numero_cni_passport',
        'date_expiration',
        'pourcentage_agence',
        'company_id',
    ];

    protected $casts = [
        'date_expiration' => 'date',
        'pourcentage_agence' => 'decimal:2',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
