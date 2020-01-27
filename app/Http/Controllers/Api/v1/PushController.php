<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Jobs\SendPushJob;
use App\Models\Credential;
use App\Models\PushDevice;
use App\Models\PushNotificationLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PushController extends Controller
{
    private $entries = 30;

    public function index(Credential $credential)
    {
        return response()->json([
            'message' => __('Showing Latest :entries entries', ['entries' => $this->entries]),
            'data' => PushNotificationLog::whereCredentialId($credential->id)
                ->orderBy('created_at', 'desc')->take($this->entries)->get()
        ]);
    }

    public function status($uuid, Credential $credential)
    {
        if ($push = PushNotificationLog::whereCredentialId($credential->id)->whereId($uuid)->first()) {
            return response()->json([
                'push_uuid' => $push->id,
                'status' => $push->status,
                'data' => $push->exception,
                'extra' => $push->additional
            ]);
        }
        return response()->json([
            'status' => 'missing',
            'push_uuid' => $uuid
        ], 404);
    }

    public function register(Request $request, Credential $credential)
    {
        $this->validate($request, [
            'platform' => ['required', 'string'],
            'identity' => ['required', 'string'],
            'regid' => ['required', 'string']
        ]);
        $platform = $request->get('platform');
        $identity = $request->get('identity');
        $regid = $request->get('regid');
        if (!$device = PushDevice::whereCredentialId($credential->id)
            ->wherePlatform($platform)->whereIdentity($identity)->first()) {
            $device = new PushDevice([
                'credential_id' => $credential->id,
                'platform' => $platform,
                'uuid' => "UID:" . sha1($credential->id . $platform . $identity),
                'identity' => $identity,
                'regid' => $regid
            ]);
            $device->save();
        }
        if ($device->regid == $regid) {
            return response()->json([
                'device_uuid' => $device->uuid
            ]);
        }
        $device->regid = $regid;
        $device->save();

        return response()->json([
            'device_uuid' => $device->uuid
        ]);

    }

    public function queue(Request $request, Credential $credential)
    {
        $details = $this->process($request, $credential);
        dispatch(new SendPushJob($details));
        return response()->json([
            'push_uuid' => $details['uuid'],
            'status' => 'queued',
            'data' => [],
            'extra' => []
        ]);
    }

    public function now(Request $request, Credential $credential)
    {
        $details = $this->process($request, $credential);
        dispatch((new SendPushJob($details))->onConnection('sync'));
        $jobResult = PushNotificationLog::whereJobId($details['uuid'])->first();
        return response()->json([
            'push_uuid' => $details['uuid'],
            'status' => $jobResult['status'],
            'data' => $jobResult['exception'],
            'extra' => $jobResult['additional']
        ]);
    }

    private function process(Request $request, Credential $credential)
    {
        $this->validate($request, [
            'to' => ['required', 'string'],
            'payload' => ['required', 'json']
        ]);
        $json_request = $request->all();
        $json_request["to"] = json_decode($json_request["to"] ?? "[]");
        $json_request["payload"] = json_decode($json_request["payload"] ?? "[]");
        $request->merge($json_request);
        $this->validate($request,
            [
                'to' => ['present', 'array'],
                'to.*' => [
                    'string',
                    'distinct',
                    Rule::exists('push_devices', 'uuid')
                        ->where('credential_id', $credential->id)
                ]
            ],
            [
                'exists' => 'The Application does not know the device :attribute'
            ]
        );
        $details = [
            'credential_id' => $credential->id,
            'uuid' => Str::uuid(),
            'to' => $request->get('to'),
            'payload' => $request->get('payload'),
        ];
        PushNotificationLog::create([
            'credential_id' => $details['credential_id'],
            'job_id' => $details['uuid'],
            'status' => 'queued',
            'payload' => $details
        ]);
        return $details;
    }
}
