<?php

namespace App\Services;

use App\Models\Token;
use App\Models\User;
use Illuminate\Support\Str;

class TokenService
{
    public function createToken(User $user):string
    {
        $user->tokens()->delete();
        $plainTextToken = Str::random(64);

        Token::create([
            'user_id' => $user->id,
            'token' => hash('sha256', $plainTextToken),
            'expires_at' => now()->addDays(7),
        ]);
        return $plainTextToken;
    }

    public function validateToken(String $plainToken): ?User{
        $hashed = hash('sha256', $plainToken);
        $token = token::where('token',$hashed)
        ->where('expires_at','>',now())
        ->first();
        return $token?->user;
    }

    public function deleteToken(String $plainToken): void{
        $hashed = hash('sha256', $plainToken);
        Token::where('token',$hashed)->delete();
    }
}
