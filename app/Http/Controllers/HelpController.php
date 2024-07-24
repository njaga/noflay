<?php

// app/Http/Controllers/HelpController.php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class HelpController extends Controller
{
    public function index()
    {
        return Inertia::render('Help');
    }
}
