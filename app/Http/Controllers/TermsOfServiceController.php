<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class TermsOfServiceController extends Controller
{
    public function index()
    {
        return Inertia::render('TermsOfService');
    }
}
