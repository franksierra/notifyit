<?php

namespace App\Http\Controllers\api\v1\Emails;

use App\Jobs\SendEmailJob;
use App\Http\Controllers\Controller;

class EmailsController extends Controller
{
    public function send()
    {
        $details['email'] = 'sierrafayad@gmail.com';
        $details['uid'] = uniqid("mail_");
        dispatch(new SendEmailJob($details));
        dd($details['uid']);
    }
}
