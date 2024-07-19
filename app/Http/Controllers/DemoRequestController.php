<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\DemoRequestMail;

class DemoRequestController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'company' => 'required|string|max:255',
        ]);

        $demoRequestData = $request->only(['name', 'email', 'phone', 'company']);

        // Envoyer un email
        Mail::to('contact@ndiagandiaye.com')->send(new DemoRequestMail($demoRequestData));

        return response()->json(['success' => true]);
    }
}
