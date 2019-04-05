<?php

namespace App\Http\Middleware;

use App\Models\AppKey;
use App\Models\RequestLog;
use Illuminate\Http\Request;
use Closure;

class ApiAuthorization
{

    const AUTH_HEADER = 'X-Authorization';

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $header = $request->header(self::AUTH_HEADER);
        $apiKey = AppKey::getByKey($header);

        // Aqui voy a guardar las peticiones
        if ($apiKey instanceof AppKey) {
            $this->logAccessEvent($request, $apiKey);
            return $next($request);
        }

        $this->logAccessEvent($request);
        return response([
            'errors' => [[
                'message' => 'Unauthorized'
            ]]
        ], 401);

    }

    protected function logAccessEvent(Request $request, AppKey $apiKey = null)
    {
        $request_log = new RequestLog;
        $request_log->save([
            'origin' => 'api',
            'app_id' => 'Unauthorized',
            'message' => 'Unauthorized',
            'message' => 'Unauthorized',
        ]);


//        $event = new ApiKeyAccessEvent;
//        $event->api_key_id = $apiKey->id;
//        $event->ip_address = $request->ip();
//        $event->url        = $request->fullUrl();
//        $event->save();
    }
}
