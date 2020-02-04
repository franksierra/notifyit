<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use App\Models\Credential;
use App\Models\EmailNotificationLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EmailsController extends Controller
{
    private $entries = 30;

    public function index(Credential $credential)
    {
        return response()->json([
            'message' => __('Showing Latest :entries entries', ['entries' => $this->entries]),
            'data' => EmailNotificationLog::whereCredentialId($credential->id)
                ->orderBy('created_at', 'desc')->take($this->entries)->get()
        ]);
    }

    public function status($uuid, Credential $credential)
    {
        if ($email = EmailNotificationLog::whereCredentialId($credential->id)->whereId($uuid)->first()) {
            return response()->json([
                'mail_uuid' => $email->id,
                'status' => $email->status,
                'data' => $email->exception,
                'extra' => $email->additional
            ]);
        }

        return response()->json([
            'status' => 'missing',
            'mail_uuid' => $uuid
        ], 404);
    }

    public function queue(Request $request, Credential $credential)
    {
        $details = $this->process($request, $credential);
        dispatch(new SendEmailJob($details));
        return response()->json([
            'mail_uuid' => $details['uuid'],
            'status' => 'queued',
            'data' => [],
            'extra' => []
        ]);
    }

    public function now(Request $request, Credential $credential)
    {
        $details = $this->process($request, $credential);
        dispatch((new SendEmailJob($details))->onConnection('sync'));
        $jobResult = EmailNotificationLog::whereJobId($details['uuid'])->first();
        return response()->json([
            'mail_uuid' => $details['uuid'],
            'status' => $jobResult['status'],
            'data' => $jobResult['exception'],
            'extra' => $jobResult['additional']
        ]);
    }

    private function process(Request $request, Credential $credential)
    {
        $this->validate($request, [
            'name' => ['required', 'max:255'],
            'from' => ['required', 'email', 'max:255'],
            'reply_to' => ['nullable', 'email', 'max:255'],

            'to' => ['nullable', 'json'],
            'cc' => ['nullable', 'json'],
            'bcc' => ['nullable', 'json'],

            'subject' => ['required', 'max:255'],

            'body' => ['required', 'string'],
            'alt_body' => ['nullable', 'string'],

            'embedded' => ['nullable', 'json'],
            'attachments' => ['nullable', 'json'],
        ]);
        $jsonRequest = $request->all();
        $jsonRequest["to"] = json_decode($jsonRequest["to"] ?? "[]");
        $jsonRequest["cc"] = json_decode($jsonRequest["cc"] ?? "[]");
        $jsonRequest["bcc"] = json_decode($jsonRequest["bcc"] ?? "[]");
        $jsonRequest['mails'] = array_merge($jsonRequest["to"], $jsonRequest["cc"], $jsonRequest["bcc"]);
        $jsonRequest["embedded"] = json_decode($jsonRequest["embedded"] ?? "[]", JSON_OBJECT_AS_ARRAY);
        $jsonRequest["attachments"] = json_decode($jsonRequest["attachments"] ?? "[]", JSON_OBJECT_AS_ARRAY);
        $request->merge($jsonRequest);

        $this->validate($request, [
            'mails' => ['required', 'array', 'min:1'],
            'to.*' => ['email'],
            'cc.*' => ['email'],
            'bcc.*' => ['email'],

            'embedded' => ['present', 'array'],
            'embedded.*.name' => ['required', 'string', 'distinct'],
            'embedded.*.format' => ['required', 'string'],
            'embedded.*.b64' => ['required', 'string'],

            'attachments' => ['present', 'array'],
            'attachments.*.name' => ['required', 'string', 'distinct'],
            'attachments.*.format' => ['required', 'string'],
            'attachments.*.b64' => ['required', 'string'],
        ]);
        $details = [
            'credential_id' => $credential->id,
            'uuid' => Str::uuid()->toString(),

            'name' => $request->get('name'),
            'from' => $request->get('from'),
            'reply_to' => $request->get('reply_to', $request->get('from')),

            'to' => $request->get('to'),
            'cc' => $request->get('cc'),
            'bcc' => $request->get('bcc'),
            'subject' => $request->get('subject'),
            'body' => $request->get('body'),
            'alt_body' => $request->get('alt_body', ''),
            'embedded' => $request->get('embedded'),
            'attachments' => $request->get('attachments'),
        ];
        $details = $this->storeData($details);
        EmailNotificationLog::create([
            'credential_id' => $details['credential_id'],
            'job_id' => $details['uuid'],
            'status' => 'queued',
            'payload' => $details
        ]);
        return $details;
    }

    /**
     * @param array $details
     *
     * @return array
     */
    private function storeData($details)
    {
        $filePath = "emails/{$details['credential_id']}/{$details['uuid']}/";
        $bodyFileName = "body.html";
        $altBodyFileName = "alt_body.txt";

        Storage::disk('local')->put($filePath . $bodyFileName, $details['body']);
        $details['body'] = $filePath . $bodyFileName;

        Storage::disk('local')->put($filePath . $altBodyFileName, $details['alt_body']);
        $details['alt_body'] = $filePath . $bodyFileName;

        $embedded = $details['embedded'];
        $details['embedded'] = [];
        foreach ($embedded as $item) {
            $fileName = "E_{$item["name"]}.{$item["format"]}";
            Storage::disk('local')->put($filePath . $fileName, base64_decode($item["b64"]));
            $details['embedded'][] = [
                'format' => $item["format"],
                'name' => $item["name"],
                'file' => $filePath . $fileName
            ];
        }

        $attachments = $details['attachments'];
        $details['attachments'] = [];
        foreach ($attachments as $item) {
            $fileName = "A_{$item["name"]}.{$item["format"]}";
            Storage::disk('local')->put($filePath . $fileName, base64_decode($item["b64"]));
            $details['attachments'][] = [
                'format' => $item["format"],
                'name' => $item["name"],
                'file' => $filePath . $fileName
            ];
        }

        return $details;
    }
}
