<?php

namespace App\Http\Controllers\Api\v1;

use App\Jobs\SendSMSJob;
use App\Models\SmsLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class SMSController extends Controller
{
    public function status($uuid, Request $request)
    {
        $app_id = $request->request_log->app_id;
        $sms = SmsLog::whereAppId($app_id)->whereUuid($uuid)->first();
        if ($sms) {
            return response()->json([
                'sms_uuid' => $uuid,
                'status' => $sms->status,
                'data' => json_decode($sms->data)
            ]);
        } else {
            return response()->json(
                [
                    'status' => 'missing',
                    'sms_uuid' => $uuid
                ],
                404
            );
        }
    }

    private function proccess(Request $request)
    {
        $this->validate($request, [
            'country' => 'required|max:2',
            'to' => 'required|json',
            'text' => 'required|string',
        ]);
        $json_request = $request->all();
        $json_request["to"] = json_decode($json_request["to"]);
        $request->merge($json_request);
        $this->validate($request, [
            'to.*' => 'string|distinct'
        ]);
        $details = [
            'app_id' => $request->request_log->app_id,
            'uuid' => Str::uuid(),

            'country' => $request->get('country'),
            'to' => $request->get('to'),
            'text' => $request->get('text')
        ];

        (new SmsLog)->create([
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
        dispatch(new SendSMSJob($details));
        return response()->json([
            'sms_uuid' => $details['uuid'],
            'status' => 'queued',
            'data' => json_encode([])
        ]);
    }

    public function now(Request $request)
    {
        $details = $this->proccess($request);
        $dispatch = dispatch_now(new SendSMSJob($details));
        return response()->json([
            'sms_uuid' => $details['uuid'],
            'status' => $dispatch['status'],
            'data' => json_decode($dispatch['data'])
        ]);
    }
}
