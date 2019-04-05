<?php

namespace App\Http\Controllers\api\v1\Emails;

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
            'to' => 'required|email',
            'subject' => 'required|max:255',
            'body' => 'required',
        ]);

        $details = [
            'app_id' => 0,

            'uid' => uniqid("mail_"),
            'name' => $request->get('name'),
            'from' => $request->get('from'),
            'reply_to' => $request->get('reply_to', $request->get('from')),

            'to' => $request->get('to'),
            'cc' => $request->get('cc', '[]'),
            'bcc' => $request->get('bcc', '[]'),
            'subject' => $request->get('subject'),
            'body' => $request->get('body'),
            'alt_body' => $request->get('alt_body', ''),

            'embedded' => $request->get('embedded', '[]'),
            'attachments' => $request->get('attachments', '[]'),

        ];
        dispatch(new SendEmailJob($details));



        return response()->json([
            'mail_uid' => $details['uid']
        ]);
    }
}
