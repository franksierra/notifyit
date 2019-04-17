<?php

namespace App\Http\Controllers\api\v1;

use App\Jobs\SendEmailJob;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmailsController extends Controller
{
    public function queue(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'from' => 'required|email|max:255',
            'reply_to' => 'nullable|email|max:255',

            'to' => 'nullable|json',
            'cc' => 'nullable|json',
            'bcc' => 'nullable|json',

            'subject' => 'required|max:255',

            'body' => 'required|string',
            'alt_body' => 'nullable|string',

            'embedded' => 'nullable|json',
            'attachments' => 'nullable|json',
        ]);
        $json_request = $request->all();
        $json_request["to"] = json_decode($json_request["to"] ?? "[]");
        $json_request["cc"] = json_decode($json_request["cc"] ?? "[]");
        $json_request["bcc"] = json_decode($json_request["bcc"] ?? "[]");
        $json_request['mails'] = array_merge($json_request["to"], $json_request["cc"], $json_request["bcc"]);
        $json_request["embedded"] = json_decode($json_request["embedded"] ?? "[]", JSON_OBJECT_AS_ARRAY);
        $json_request["attachments"] = json_decode($json_request["attachments"] ?? "[]", JSON_OBJECT_AS_ARRAY);
        $request->merge($json_request);
        $this->validate($request, [
            'mails' => 'required|array|min:1',
            'to.*' => 'email|distinct',
            'cc.*' => 'email|distinct',
            'bcc.*' => 'email|distinct',

            'embedded' => 'present|array',
            'embedded.*.name' => 'required|string|distinct',
            'embedded.*.format' => 'required|string',
            'embedded.*.b64' => 'required|string',

            'attachments' => 'present|array',
            'attachments.*.name' => 'required|string|distinct',
            'attachments.*.format' => 'required|string',
            'attachments.*.b64' => 'required|string',
        ]);
        $details = [
            'app_id' => $request->request_log->app_id,

            'uid' => uniqid("mail_"),
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

//        dispatch(new SendEmailJob($details));
        dispatch_now(new SendEmailJob($details));
        return response()->json([
            'mail_uid' => $details['uid']
        ]);
    }
}
