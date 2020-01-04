<?php

namespace App\Http\Middleware;

use App\Models\AppKey;
use App\Models\RequestLog;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Closure;

class ApiAuthorization
{

    const AUTH_HEADER = 'X-Authorization';

    /** @var RequestLog */
    private $requestLog;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $header = $request->header(self::AUTH_HEADER);
        $apiKey = AppKey::getByKey($header);

        $this->requestLog = new RequestLog([
            'origin' => 'api',
            'method' => $request->getMethod(),
            'app_id' => "k_" . $header,
            'uri' => $request->getRequestUri(),
            'headers' => json_encode($request->headers->all()),
            'params' => json_encode($request->request->all()),
            'ip' => $request->getClientIp(),
        ]);
        $this->requestLog->save();

        if ($apiKey instanceof AppKey) {
            $this->requestLog->app_id = $apiKey->app_id;
            $this->requestLog->save();
            $request->request->add(['request_log' => $this->requestLog]);
            return $next($request);
        }

        return response([
            'errors' => [[
                'message' => 'Unauthorized'
            ]]
        ], 401);

    }

    public function terminate(Request $request, Response $response)
    {
        $this->requestLog->status_code = $response->getStatusCode();
        $this->requestLog->response = json_encode($response->getOriginalContent());
        $this->requestLog->exec_time = microtime(true) - LARAVEL_START;
        $this->requestLog->save();
    }
}
