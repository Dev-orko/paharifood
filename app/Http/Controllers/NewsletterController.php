<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NewsletterSubscription;
use Illuminate\Validation\ValidationException;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        // Check if email already exists
        $existingSubscription = NewsletterSubscription::where('email', $request->email)->first();
        
        if ($existingSubscription) {
            return response()->json([
                'success' => false,
                'message' => 'Already subscribed'
            ], 422);
        }

        // Validate request
        $validated = $request->validate([
            'email' => 'required|email|unique:newsletter_subscriptions,email',
        ]);

        // Create subscription
        $subscription = NewsletterSubscription::create([
            'email' => $validated['email'],
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Successfully subscribed to newsletter',
            'data' => $subscription
        ], 201);
    }
}
