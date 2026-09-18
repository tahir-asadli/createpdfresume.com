<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function __invoke(Request $request)
    {

        // $file = $request->file('avatar');
        // $path = $file->store('uploads', 'public');
        // dump(
        //     [
        //         'root' => storage_path('app/public'),
        //         'url' => env('APP_URL') . '/storage',
        //     ]
        // );

        return 'uploading';
    }
}
