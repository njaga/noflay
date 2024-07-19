<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:5000',
        ]);

        $contactFormData = $request->only(['name', 'email', 'message']);

        // Envoyer un email
        Mail::to('contact@ndiagandiaye.com')->send(new ContactFormMail($contactFormData));

        return response()->json(['success' => true]);
    }
}
