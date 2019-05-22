<?php

namespace App\Http\Controllers\Api\v1;

use App\Jobs\SendPushJob;
use App\Models\PushDevice;
use App\Models\PushLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Support;

class PushController extends Controller
{

    public function status($uuid, Request $request)
    {
        $app_id = $request->request_log->app_id;
        $push = PushLog::whereAppId($app_id)->whereUuid($uuid)->first();
        if ($push) {
            return response()->json([
                'push_uuid' => $uuid,
                'status' => $push->status,
                'data' => json_decode($push->data)
            ]);
        } else {
            return response()->json(
                [
                    'status' => 'missing',
                    'push_uuid' => $uuid
                ],
                404
            );
        }
    }

    private function proccess(Request $request)
    {
        $this->validate($request, [
            'to' => 'required|string',
            'payload' => 'required|json'
        ]);
        $json_request = $request->all();
        $json_request["to"] = json_decode($json_request["to"] ?? "[]");
        $json_request["payload"] = json_decode($json_request["payload"] ?? "[]");
        $request->merge($json_request);
        $this->validate($request,
            [
                'to' => 'present|array',
                'to.*' => [
                    'string',
                    'distinct',
                    Rule::exists('push_devices', 'uuid')
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
            'payload' => $request->get('payload'),
            'uuid' => Support\Str::uuid()
        ];
        (new PushLog)->create([
            'app_id' => $details['app_id'],
            'uuid' => $details['uuid'],
            'status' => 'queued',
            'data' => json_encode([])
        ]);
        return $details;
    }

    public function queue(Request $request)
    {
        $details = $this->proccess($request);
        dispatch(new SendPushJob($details));
        return response()->json([
            'push_uuid' => $details['uuid'],
            'status' => 'queued',
            'data' => json_encode([])
        ]);
    }

    public function now(Request $request)
    {
        $details = $this->proccess($request);
        $dispatch = dispatch_now(new SendPushJob($details));
        return response()->json([
            'push_uuid' => $details['uuid'],
            'status' => $dispatch['status'],
            'data' => json_decode($dispatch['data'])
        ]);
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'platform' => 'required|string',
            'identity' => 'required|string',
            'regid' => 'required|string'
        ]);
        $app_id = $request->request_log->app_id;
        $platform = $request->get('platform');
        $identity = $request->get('identity');
        $uuid = "UID:" . sha1($app_id . $platform . $identity);
        $regid = $request->get('regid');

        if (!PushDevice::whereUuid($uuid)->exists()) {
            $device = new PushDevice([
                'app_id' => $app_id,
                'platform' => $platform,
                'uuid' => $uuid,
                'identity' => $identity,
                'regid' => $regid
            ]);
            $device->save();
            return response()->json([
                'device_uuid' => $device->uuid
            ]);

        }

        $device = PushDevice::whereUuid($uuid)->first();
        if ($device->regid == $regid) {
            return response()->json([
                'device_uuid' => $device->uuid
            ]);
        }
//        TODO: Create this Log Table
//        (new PushDeviceLog)->create([
//            'device_id' => $device->id,
//            'old_regid' => $device->regid,
//            'new_regid' => $regid,
//
//        ])
        $device->regid = $regid;

        $device->save();

        return response()->json([
            'device_uuid' => $device->uuid
        ]);
    }
}
