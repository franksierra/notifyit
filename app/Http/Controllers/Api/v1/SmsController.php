<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Jobs\SendSmsJob;
use App\Models\Credential;
use App\Models\SmsNotificationLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SmsController extends Controller
{
    private $entries = 30;

    public function index(Credential $credential)
    {
        return response()->json([
            'message' => __('Showing Latest :entries entries', ['entries' => $this->entries]),
            'data' => SmsNotificationLog::whereCredentialId($credential->id)
                ->orderBy('created_at', 'desc')->take($this->entries)->get()
        ]);
    }

    public function status($uuid, Credential $credential)
    {
        if ($sms = SmsNotificationLog::whereCredentialId($credential->id)->whereId($uuid)->first()) {
            return response()->json([
                'sms_uuid' => $sms->id,
                'status' => $sms->status,
                'data' => $sms->exception,
                'extra' => $sms->additional
            ]);
        }

        return response()->json([
            'status' => 'missing',
            'sms_uuid' => $uuid
        ], 404);
    }

    public function queue(Request $request, Credential $credential)
    {
        $details = $this->process($request, $credential);
        dispatch(new SendSmsJob($details));
        return response()->json([
            'sms_uuid' => $details['uuid'],
            'status' => 'queued',
            'data' => [],
            'extra' => []
        ]);
    }

    public function now(Request $request, Credential $credential)
    {
        $details = $this->process($request, $credential);
        dispatch((new SendSmsJob($details))->onConnection('sync'));
        $jobResult = SmsNotificationLog::whereJobId($details['uuid'])->first();
        return response()->json([
            'sms_uuid' => $details['uuid'],
            'status' => $jobResult['status'],
            'data' => $jobResult['exception'],
            'extra' => $jobResult['additional']
        ]);
    }

    private function process(Request $request, Credential $credential)
    {
        $this->validate($request, [
            'to' => ['required', 'json'],
            'text' => ['required', 'string']
        ]);
        $json_request = $request->all();
        $json_request["to"] = json_decode($json_request["to"]);
        $request->merge($json_request);
        $this->validate($request, [
            'to.*' => ['string', 'distinct']
        ]);
        $details = [
            'credential_id' => $credential->id,
            'uuid' => Str::uuid()->toString(),

            'to' => $request->get('to'),
            'text' => $request->get('text')
        ];

        SmsNotificationLog::create([
            'credential_id' => $details['credential_id'],
            'job_id' => $details['uuid'],
            'status' => 'queued',
            'payload' => $details
        ]);

        return $details;
    }
}
