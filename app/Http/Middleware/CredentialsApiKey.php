<?php

namespace App\Http\Middleware;

use App\Models\Credential;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class CredentialsApiKey
{
    const AUTH_HEADER = 'X-Authorization';

    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws AuthorizationException
     */
    public function handle(Request $request, Closure $next)
    {
        $header = $request->header(self::AUTH_HEADER);
        $credential = Credential::whereApiKey($header)->first();
        if ($credential instanceof Credential) {
            app()->bind(Credential::class, function () use ($credential) {
                return $credential;
            });
            return $next($request);
        }
        throw new AuthorizationException();
    }
}
