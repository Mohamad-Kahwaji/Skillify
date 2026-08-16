<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireVerifiedIdentity
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth('users')->user();
        $verified = $user?->identityVerification()->where('status', 'approved')->exists();

        if (!$verified) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'يجب توثيق هويتك أولًا حتى تتمكن من إنشاء خدمة.',
                    'verification_required' => true,
                ], 403);
            }

            return redirect()->back()->with('error', 'يجب توثيق هويتك أولًا حتى تتمكن من إنشاء خدمة.');
        }

        return $next($request);
    }
}
