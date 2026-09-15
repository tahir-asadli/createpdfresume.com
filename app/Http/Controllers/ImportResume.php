<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadPropertyImageRequest;
use App\Models\Property;
use App\Models\Image;
use App\Services\ResumeImporter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImportResume extends Controller
{

  public function __invoke(Request $request)
  {
    $file = $request->file("resume");
    $validator = Validator::make($request->all(), [
      'resume' => 'required|file|mimes:pdf,docx,txt|max:5120',
    ]);

    if ($validator->fails()) {
      return ['status' => 'error', 'message' => __('Error'), 'errors' => $validator->errors()];
    }
    $filePath = $file->store('temp', 'local');
    $pathToFile = Storage::disk('local')->path($filePath);
    $resumeImporter = new ResumeImporter($pathToFile, auth()->user());

    $resumeImporter->import();

    if ($resumeImporter->isSuccessful()) {
      return ['status' => 'success', 'message' => __('Resume imported.')];
    } else {
      return ['status' => 'error', 'message' => $resumeImporter->getError()];
    }

  }

}
