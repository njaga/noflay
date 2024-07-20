<?php

// app/Http/Controllers/MandatController.php

namespace App\Http\Controllers;

use App\Models\Landlord;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MandatController extends Controller
{
    public function create()
    {
        $landlords = Landlord::with('properties')->get();

        return Inertia::render('Mandat/Create', [
            'landlords' => $landlords
        ]);
    }
}

