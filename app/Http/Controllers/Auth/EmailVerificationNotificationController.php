<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): mixed
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Your email has already been verified.',
            ], 200);
        }

        if (
            $request->user()->email_verification_sent_at &&
            $request->user()->email_verification_sent_at->gt(Carbon::now()->subMinutes(30))
        ) {
            return response()->json([
                'message' => 'You must wait at least 30 minutes to send a new email confirmation.',
            ], 429);
        }

        $request->user()->sendEmailVerificationNotification();
        $request->user()->update(['email_verification_sent_at' => Carbon::now()]);

        return response()->json([
            'message' => 'Email verification successfully sent.',
        ], 200);
    }
}
