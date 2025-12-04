<?php

namespace App\Resolvers;

use OwenIt\Auditing\Contracts\UserResolver;

class CustomUserResolver implements UserResolver
{
    /**
     * Resolve the User for auditing.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public static function resolve()
    {
        // Debug logging to see what's happening
        \Log::info('Custom Audit resolver called', [
            'has_bearer' => request()->bearerToken() ? 'yes' : 'no',
            'sanctum_user' => auth('sanctum')->id(),
            'web_user' => auth('web')->id(),
            'request_path' => request()->path(),
            'is_api_request' => request()->is('api/*')
        ]);

        // Try Sanctum first (Bearer tokens) - this is the key fix for API requests
        if (request()->bearerToken() && auth('sanctum')->check()) {
            $user = auth('sanctum')->user();
            \Log::info('Audit: Using Sanctum user', ['user_id' => $user->id]);
            return $user;
        }

        // Try web guard (sessions) - for web requests
        if (auth('web')->check()) {
            $user = auth('web')->user();
            \Log::info('Audit: Using Web user', ['user_id' => $user->id]);
            return $user;
        }

        // Try API guard as fallback
        if (auth('api')->check()) {
            $user = auth('api')->user();
            \Log::info('Audit: Using API user', ['user_id' => $user->id]);
            return $user;
        }

        \Log::warning('Audit: No user found for auditing', [
            'guards_checked' => ['sanctum', 'web', 'api'],
            'current_auth' => auth()->id()
        ]);
        
        return null;
    }
}