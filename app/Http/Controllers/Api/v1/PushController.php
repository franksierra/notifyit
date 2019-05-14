<?php

namespace App\Http\Controllers\Api\v1;

use App\Jobs\SendPushJob;
use App\Models\PushLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support;

class PushController extends Controller
{
    public function queue(Request $request)
    {
        $this->validate($request, [
            'to' => 'required|string',
            'data' => 'required|string',
            'notification' => 'nullable|string'
        ]);
        $json_request = $request->all();
        $json_request["to"] = json_decode($json_request["to"] ?? "[]");
        $request->merge($json_request);
        $this->validate(
            $request,
            [
                'to' => 'present|array',
                'to.*' => [
                    'string',
                    'distinct',
                    Rule::exists('push_devices', 'uid')
                        ->where('app_id', $request->request_log->app_id)
                ]
            ],
            [
                'exists' => 'The Application does not know the device :attribute'
            ]
        );
        $details = [
            'app_id' => $request->request_log->app_id,
            'to' => $request->get('to'),
            'data' => $request->get('data'),
            'notification' => $request->get('notification'),
            'uuid' => Support\Str::uuid()
        ];

        PushLog::create([
            'uuid' => $details['uuid'],
            'status' => 'queued',
            'data' => json_encode([])
        ]);
        dispatch_now(new SendPushJob($details));
        return response()->json([
            'push_uuid' => $details['uuid']
        ]);
    }
}
