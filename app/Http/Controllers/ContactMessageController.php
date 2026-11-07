<?php

namespace App\Http\Controllers;

use App\Models\PortfolioMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:190'],
            'phone' => ['nullable', 'string', 'max:40'],
            'subject' => ['required', 'string', 'max:180'],
            'message' => ['required', 'string', 'max:10000'],
        ]);

        PortfolioMessage::create($validated);

        return redirect('/#kontak')->with('contact_sent', 'Terima kasih. Pesan Anda sudah terkirim.');
    }
}
