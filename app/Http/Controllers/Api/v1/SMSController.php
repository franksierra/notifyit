<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SMSController extends Controller
{
    public function queue(Request $request)
    {
        $this->validate($request, [
            'country' => 'required|max:2',
            'to' => 'required|json',
            'body' => 'required|string',
        ]);
        $json_request = $request->all();
        $json_request["to"] = json_decode($json_request["to"]);
        $request->merge($json_request);
        $this->validate($request, [
            'to.*' => 'number|distinct'
        ]);

        dispatch_now(new SendSMSJob($details));
        return response()->json([
            'mail_uid' => $details['uid']
        ]);
    }
}
