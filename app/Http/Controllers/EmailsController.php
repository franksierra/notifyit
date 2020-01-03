<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Laravel\Telescope\Contracts\EntriesRepository;

class EmailsController extends Controller
{

    /**
     * Download the Eml content of the email.
     *
     * @param string $uuid
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function download($uuid)
    {
        $name = 'mails/' . $uuid . '.eml';
        $file = Storage::disk('public')->get($name);

        return response($file, 200, [
            'Content-Type' => 'message/rfc822',
            'Content-Disposition' => 'attachment; filename="mail-' . $uuid . '.eml"',
        ]);
    }
}
