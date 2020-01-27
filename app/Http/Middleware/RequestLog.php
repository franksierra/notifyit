<?php

namespace App\Http\Middleware;

use App\Models\Credential;
use App\Models\Request as RequestModel;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class RequestLog
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $requestModel = new RequestModel([
            'method' => $request->getMethod(),
            'uri' => $request->getRequestUri(),
            'headers' => $request->headers->all(),
            'params' => $request->request->all(),
            'ip' => $request->getClientIp(),
        ]);
        $requestModel->save();
        app()->bind(RequestModel::class, function () use ($requestModel) {
            return $requestModel;
        });
        return $next($request);
    }

    /**
     * @param Request $request
     * @param Response|JsonResponse $response
     */
    public function terminate(Request $request, $response)
    {
        $credential = app(Credential::class);
        $requestModel = app(RequestModel::class);
        $requestModel->credential_id = $credential->id;
        $requestModel->status_code = $response->getStatusCode();
        $requestModel->response = $response->getOriginalContent();
        $requestModel->exec_time = microtime(true) - LARAVEL_START;
        $requestModel->save();
    }
}
