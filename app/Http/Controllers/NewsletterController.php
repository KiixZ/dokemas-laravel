<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NewsletterController extends Controller
{
    public function showForm()
    {
        return view('newsletter.subscribe');
    }

    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:newsletter_subscribers',
            'company' => 'required|string|max:255',
            'description' => 'required|string|min:50|max:1000',
        ]);

        $subscriber = NewsletterSubscriber::create($validated);

        // Kirim notifikasi ke admin (bisa diimplementasikan nanti)
        // Mail::to('admin@example.com')->send(new NewSubscriberNotification($subscriber));

        return redirect()
            ->back()
            ->with('success', 'Terima kasih telah mendaftar! Kami akan menghubungi Anda setelah mereview pendaftaran Anda.');
    }
}

