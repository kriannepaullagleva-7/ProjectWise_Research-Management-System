<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;

class ResendVerificationEmail
{
    /**
     * Resend the email verification notification.
     */
    public function __invoke(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', 'Verification link sent!');
    }
}
