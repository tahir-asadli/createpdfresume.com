<?php

namespace App\Services;

use App\Jobs\GenerateTransparentProfileImage;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ResumeImporter
{

  protected string $error = '';
  protected bool $successful = false;
  protected string $resumeTextContent = '';
  protected string $textFileName = '';
  protected string $imagesFolderName = '';
  protected string $resumeTextsPath = '';
  protected string $outputTextFile = '';
  protected string $resumeImagesPath = '';


  public function __construct(public string|null $pathToResumeFile = null, public User $user)
  {
    $this->textFileName = date("d-m-Y") . '-' . uniqid() . '.txt';
    $this->resumeTextsPath = storage_path('app/private/resume-import-data');
    $this->outputTextFile = "$this->resumeTextsPath/$this->textFileName";
    $this->imagesFolderName = date("d-m-Y") . '-' . uniqid();
    $this->resumeImagesPath = $this->resumeTextsPath . '/' . $this->imagesFolderName;
  }

  public function getError()
  {
    return $this->error;
  }

  public function isSuccessful()
  {
    return $this->successful;
  }


  public function import()
  {
    $debug = app()->environment('local') ? false : false;
    $schemaPath = resource_path('import-schema.json');
    $schemaContent = file_get_contents($schemaPath);
    $testResponse = storage_path('app/private/resume-import-data/response.json');
    if (empty($this->pathToResumeFile) || !is_file($this->pathToResumeFile)) {
      $this->error = __('Resume file not found!');
      return;
    }
    if (!$this->user instanceof User) {
      $this->error = __('User not found!');
      return;
    }
    if (!$schemaPath || !is_file($schemaPath)) {
      $this->error = __('Error occured!, code: #1');
      return;
    }

    $this->createFolders();

    $fileExtension = strtolower(pathinfo($this->pathToResumeFile, PATHINFO_EXTENSION));

    if ($fileExtension === 'pdf') {
      $this->resumeTextContent = $this->extractPDFContent();
    }
    if ($fileExtension === 'txt') {
      $this->resumeTextContent = trim(file_get_contents($this->pathToResumeFile));
    } else {
      $this->resumeTextContent = $this->extractWordContent();
    }

    if (!$this->resumeTextContent) {
      $this->error = __('Resume is empty');
      return;
    }

    if ($debug) {
      $jsonContent = json_decode(file_get_contents($testResponse), true);
      $this->successful = true;
    } else {
      $jsonContent = $this->geminiAnalyzeResumePDF($this->resumeTextContent, $schemaContent);
      if (isset($jsonContent['error'])) {
        $this->error = __('JSON parse error!');
        info($jsonContent['error']);
        return;
      }
      $this->successful = true;
      file_put_contents($testResponse, json_encode($jsonContent));
    }
    $this->extractImages($fileExtension);
    $this->importResumeData($jsonContent);
    // $this->removeTempFiles();


  }

  protected function createFolders()
  {
    if (!is_dir($this->resumeTextsPath) && !is_file($this->resumeTextsPath)) {
      mkdir($this->resumeTextsPath, 0775);
      file_put_contents($this->resumeTextsPath . '/.gitignore', "*\n!.gitignore\n");
    }
  }

  protected function removeTempFiles()
  {
    if ($this->outputTextFile && is_file($this->outputTextFile)) {
      unlink($this->outputTextFile);
    }

    if ($this->resumeImagesPath && is_dir($this->resumeImagesPath)) {
      $files = array_diff(scandir($this->resumeImagesPath), array('.', '..'));

      foreach ($files as $file) {
        $filePath = $this->resumeImagesPath . '/' . $file;
        unlink($filePath);
      }


      rmdir($this->resumeImagesPath);
    }


  }

  protected function extractWordContent()
  {
    $officeParsePath = config('site.officeParsePath');

    $command = "$officeParsePath $this->pathToResumeFile --toText=true > $this->outputTextFile";
    $commandResult = Process::run("$command");
    if ($commandResult->successful()) {
      if (is_file($this->outputTextFile)) {
        return trim(file_get_contents($this->outputTextFile));
      }
    }
    return '';
  }

  protected function extractPDFContent()
  {
    $pdfParsePath = config('site.pdfParsePath');

    $command = "$pdfParsePath text $this->pathToResumeFile -o=$this->outputTextFile";
    $commandResult = Process::run("$command");
    if ($commandResult->successful()) {
      if (is_file($this->outputTextFile)) {
        return trim(file_get_contents($this->outputTextFile));
      }
    }
    return '';
  }

  protected function extractImages($fileExtension)
  {
    if (!$fileExtension) {
      return;
    }
    if (!is_dir($this->resumeImagesPath) && !is_file($this->resumeImagesPath)) {
      mkdir($this->resumeImagesPath, 0775, true);
      file_put_contents($this->resumeImagesPath . '/.gitignore', "*\n!.gitignore\n");
    }
    if ($fileExtension === 'pdf') {
      $png = $this->extractPDFImages();
    } else {
      $png = null;
    }
    if ($png) {
      $manager = new ImageManager(new Driver());
      $image = $manager->read($png);
      $image->scale(width: 300);
      $image->toWebp()->save($png);
      $uuid = Str::uuid();
      $imageFilename = "$uuid.webp";
      $newProfileImagePath = storage_path("app/public/photos/{$imageFilename}");
      copy($png, $newProfileImagePath);
      $profile = $this->user->profile;
      $profile->profile_image = "photos/$imageFilename";
      $profile->save();
      GenerateTransparentProfileImage::dispatch($profile);
    }
  }

  protected function extractPDFImages()
  {
    $pdfParsePath = config('site.pdfParsePath');
    $command = "$pdfParsePath image $this->pathToResumeFile -m=200 -o=$this->resumeImagesPath";
    $commandResult = Process::run("$command");
    if ($commandResult->successful()) {
      $pngs = File::glob($this->resumeImagesPath . '/*.png');
      return !empty($pngs[0]) ? $pngs[0] : null;
    }
    return null;
  }

  // protected function extractWordImages()
  // {
  //   $wordParsePath = config('site.wordParsePath');
  //   $command = "$wordParsePath --extractAttachments=true $this->pathToResumeFile";
  //   $commandResult = Process::run("$command");
  //   if ($commandResult->successful()) {
  //     $pngs = File::glob($this->resumeImagesPath . '/*.png');
  //     return !empty($pngs[0]) ? $pngs[0] : null;
  //   }
  //   return null;
  // }

  protected function geminiAnalyzeResumePDF($resumeText, $schemaContent)
  {
    $apiKey = config('site.geminiAPIKey');

    $apiEndpoint = config('site.geminiAPIEndpoint');

    $prompt = 'Extract information from this resume text and return it in JSON format following the schema I provided:
Resume Text: ' . $resumeText . ' 
Schema:
' . $schemaContent . '

Return ONLY valid JSON, no markdown formatting or explanations.';
    $body = [
      'contents' => [
        [
          'parts' => [
            [
              'text' => $prompt
            ],
          ]
        ]
      ],
      'generationConfig' => [
        'response_mime_type' => 'application/json'
      ]
    ];

    $response = Http::withHeaders([
      'Content-Type' => 'application/json',
    ])->post($apiEndpoint . '?key=' . $apiKey, $body);

    if ($response->successful()) {
      $jsonArray = $response->json();

      if (!empty($jsonArray['candidates'][0]['content']['parts'][0]['text'])) {
        $resultText = $jsonArray['candidates'][0]['content']['parts'][0]['text'];

        // Parse JSON response
        $parsedData = json_decode($resultText, true);

        if (json_last_error() === JSON_ERROR_NONE) {
          return $parsedData;
        }

        return ['error' => 'Invalid JSON response', 'raw' => $resultText];
      }

      return ['error' => 'No content in response', 'response' => $jsonArray];
    }
    info($response);
    return ['error' => 'Request failed', 'status' => $response->status(), 'body' => $response->body()];
  }

  protected function importResumeData($dataArray, $deleteAll = true)
  {

    $user = $this->user;
    if ($deleteAll) {
      $user->skills()->delete();
      $user->languages()->delete();
      $user->experiences()->delete();
      $user->educations()->delete();
      $user->projects()->delete();
      $user->certifications()->delete();
      $user->awards()->delete();
      $user->hobbies()->delete();
      $user->socials()->delete();
      $user->references()->delete();
    }

    $profileData = [];
    if (!empty($dataArray['full_name'])) {
      $profileData['fullname'] = purlimit($dataArray['full_name'], 60);
    } else {
      $profileData['fullname'] = '';
    }
    if (!empty($dataArray['job_title'])) {
      $profileData['job_title'] = purlimit($dataArray['job_title'], 200);
    } else {
      $profileData['job_title'] = '';
    }
    if (!empty($dataArray['about'])) {
      $profileData['about'] = purlimit($dataArray['about'], 2000);
    } else {
      $profileData['about'] = '';
    }
    if (!empty($dataArray['phone'])) {
      $profileData['phone'] = purlimit($dataArray['phone'], 60);
    } else {
      $profileData['phone'] = '';
    }
    if (!empty($dataArray['email'])) {
      $profileData['email'] = purlimit($dataArray['email'], 60);
    } else {
      $profileData['email'] = '';
    }
    if (!empty($dataArray['website'])) {
      $profileData['web'] = purlimit($dataArray['website'], 60);
    } else {
      $profileData['web'] = '';
    }
    if (!empty($dataArray['address'])) {
      $address = is_array($dataArray['address']) ? purlimit(implode(', ', $dataArray['address']), 200) : purlimit($dataArray['address'], 200);
      $profileData['address'] = $address;
    } else {
      $profileData['address'] = '';
    }
    $user->profile()->update($profileData);
    if (!empty($dataArray['skills'])) {
      foreach ($dataArray['skills'] as $key => $skill) {
        $user->skills()->create([
          'name' => purlimit($skill, 2000),
          'level' => 100,
          'active' => true
        ]);
      }
    }
    if (!empty($dataArray['languages'])) {
      foreach ($dataArray['languages'] as $key => $language) {
        $user->languages()->create([
          'name' => purlimit($language, 60),
          'level' => 100,
          'active' => true
        ]);
      }
    }
    if (!empty($dataArray['work_experiences'])) {
      foreach ($dataArray['work_experiences'] as $key => $work_experience) {
        if (!empty($work_experience['company']) && !empty($work_experience['position'])) {
          $work_experience_data = [
            'company' => purlimit($work_experience['company'], 2000),
            'position' => purlimit($work_experience['position'], 2000),
            'about' => !empty($work_experience['about']) ? purlimit($work_experience['about'], 2000) : null,
            'location' => !empty($work_experience['address']) ? purlimit($work_experience['address'], 2000) : null,
            'start_date' => !empty($work_experience['start_date']) ? $this->formattedDate($work_experience['start_date']) : null,
            'end_date' => !empty($work_experience['end_date']) ? $this->formattedDate($work_experience['end_date']) : null,
            'active' => true,
          ];
          $user->experiences()->create($work_experience_data);
        }
      }
    }
    if (!empty($dataArray['educations'])) {
      foreach ($dataArray['educations'] as $key => $education) {
        if (!empty($education['institution']) && !empty($education['degree'])) {
          $education_data = [
            'school' => purlimit($education['institution'], 2000),
            'degree' => purlimit($education['degree'], 2000),
            'description' => !empty($education['about']) ? purlimit($education['about'], 2000) : null,
            'location' => !empty($education['address']) ? purlimit($education['address'], 2000) : null,
            'start_date' => !empty($education['start_date']) ? $this->formattedDate($education['start_date']) : null,
            'end_date' => !empty($education['end_date']) ? $this->formattedDate($education['end_date']) : null,
            'active' => true,
          ];
          $user->educations()->create($education_data);
        }
      }
    }
    if (!empty($dataArray['projects'])) {
      foreach ($dataArray['projects'] as $key => $project) {
        $date = null;
        if (!empty($project['start_date'])) {
          $date = $this->formattedDate($project['start_date']);
        } else if (!empty($project['end_date'])) {
          $date = $this->formattedDate($project['end_date']);
        }
        if (!empty($project['name'])) {
          $project_data = [
            'name' => purlimit($project['name'], 2000),
            'url' => !empty($project['link']) ? purlimit($project['link'], 2000) : null,
            'description' => !empty($project['description']) ? purlimit($project['description'], 2000) : null,
            'date' => $date,
            'active' => true,
          ];
          $user->projects()->create($project_data);
        }
      }
    }
    if (!empty($dataArray['certificates'])) {
      foreach ($dataArray['certificates'] as $key => $certificate) {
        if (!empty($certificate['name'])) {
          $date = null;
          if (!empty($certificate['start_date'])) {
            $date = $this->formattedDate($certificate['start_date']);
          } else if (!empty($certificate['end_date'])) {
            $date = $this->formattedDate($certificate['end_date']);
          }
          $certificate_data = [
            'name' => purlimit($certificate['name'], 2000),
            'organization' => !empty($certificate['organization']) ? purlimit($certificate['organization'], 2000) : null,
            'description' => !empty($certificate['description']) ? purlimit($certificate['description'], 2000) : null,
            'date' => $date,
            'active' => true,
          ];
          $user->certifications()->create($certificate_data);
        }
      }
    }
    if (!empty($dataArray['awards'])) {
      foreach ($dataArray['awards'] as $key => $award) {
        if (!empty($award['name'])) {
          $date = null;
          if (!empty($award['start_date'])) {
            $date = $this->formattedDate($award['start_date']);
          } else if (!empty($award['end_date'])) {
            $date = $this->formattedDate($award['end_date']);
          }
          $award_data = [
            'name' => purlimit($award['name'], 2000),
            'organization' => !empty($award['organization']) ? purlimit($award['organization'], 2000) : null,
            'description' => !empty($award['description']) ? purlimit($award['description'], 2000) : null,
            'date' => $date,
            'active' => true,
          ];
          $user->awards()->create($award_data);
        }
      }
    }
    if (!empty($dataArray['hobbies'])) {
      foreach ($dataArray['hobbies'] as $key => $hobby) {
        $user->hobbies()->create([
          'name' => purlimit($hobby, 2048),
          'icon' => 'running',
          'active' => true
        ]);
      }
    }
    if (!empty($dataArray['socials'])) {
      foreach ($dataArray['socials'] as $key => $social) {
        if (!empty($social['site'])) {
          $socialData = [
            'site' => $this->getSocialIcon($social['site']),
            'name' => ucfirst($social['site']),
            'url' => $this->getSocialLink($social['link']),
            'handle' => null,
            'active' => true
          ];
          $user->socials()->create($socialData);
        }
      }
    }
  }
  protected function getSocialIcon($social = 'facebook')
  {
    $icons = collect(array_keys(config('site.socials', [])))->map(function ($key) {
      return strtolower($key);
    });
    $icon = $icons->filter(function ($value) use ($social) {
      return Str::contains($value, $social);
    })->first();

    return $icon ? $icon : 'facebook';
  }
  protected function getSocialLink($link = '')
  {
    if (!empty($link)) {
      if (!Str::startsWith($link, 'https://')) {
        return "https://$link";
      } else {
        return $link;
      }
    }
    return null;
  }

  protected function formattedDate($dateStr)
  {
    if (!strtotime($dateStr)) {
      return null;
    }
    return Carbon::createFromDate($dateStr);
  }
}