<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\ExampleEmail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function show()
    {
        return view('email.send-email');
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'recipient' => 'required|email',
            'subject' => 'required|string',
            'content' => 'required|string',
        ]);

        try {
                Mail::to($request->recipient)->send(new ExampleEmail($request->subject, $request->content));
                Log::info('Email sent successfully', $validated);

                return back()->with('status', 'Email sent successfully');
            } catch (\Exception $e) {
                Log::error('Failed to send email: ' . $e->getMessage());
                return back()->with('error', 'Failed to send email');
            };
    }
}
