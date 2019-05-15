<?php

namespace App\Http\Controllers\Api\v1;

use App\Jobs\SendSMSJob;
use App\Models\SmsLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Str;

class SMSController extends Controller
{
    public function queue(Request $request)
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
        dispatch_now(new SendSMSJob($details));
        return response()->json([
            'sms_uuid' => $details['uuid']
        ]);
    }
}
