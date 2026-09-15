<?php

use App\Models\Setting;
use App\Models\Template;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Stevebauman\Purify\Facades\Purify;
use App\Models\Blacklist;

use Illuminate\Support\Facades\Storage;

if (!function_exists('getFolderSize')) {
  function getFolderSize($folder)
  {
    $size = 0;

    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folder, FilesystemIterator::SKIP_DOTS)) as $file) {
      $size += $file->getSize();
    }

    return $size;
  }
}
if (!function_exists('formatSize')) {
  function formatSize($bytes)
  {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    for ($i = 0; $bytes >= 1024 && $i < count($units) - 1; $i++) {
      $bytes /= 1024;
    }
    return round($bytes, 2) . ' ' . $units[$i];
  }
}

if (!function_exists('userTemplateFilesCount')) {
  function userTemplateFilesCount()
  {
    $folder = storage_path('app/private/user-templates');
    return is_dir($folder) ? count(scandir($folder)) - 2 : '-';
  }
}
if (!function_exists('userTemplateSize')) {
  function userTemplateSize($unit = 'MB')
  {
    $folder = storage_path('app/private/user-templates');
    return is_dir($folder) ? formatSize(getFolderSize($folder)) : '-';
  }
}
if (!function_exists('getMemoryInfo')) {
  function getMemoryInfo()
  {
    $data = file_get_contents('/proc/meminfo');
    $lines = explode("\n", $data);
    $memInfo = [];

    foreach ($lines as $line) {
      if (preg_match('/^(\w+):\s+(\d+)\s+kB$/', $line, $matches)) {
        $memInfo[$matches[1]] = (int) $matches[2]; // in KB
      }
    }

    return [
      'total' => round($memInfo['MemTotal'] / 1024, 2) . ' MB',
      'free' => round($memInfo['MemFree'] / 1024, 2) . ' MB',
      'available' => round($memInfo['MemAvailable'] / 1024, 2) . ' MB',
      'used' => round(($memInfo['MemTotal'] - $memInfo['MemAvailable']) / 1024, 2) . ' MB',
    ];
  }
}
if (!function_exists('getCpuInfo')) {
  function getCpuInfo()
  {
    $cpuInfo = [];
    $lines = file('/proc/cpuinfo');

    foreach ($lines as $line) {
      if (preg_match('/^(model name|cpu MHz|processor|cpu cores|siblings)\s+:\s+(.*)$/', $line, $matches)) {
        $cpuInfo[$matches[1]][] = $matches[2];
      }
    }

    return $cpuInfo;
  }
}
if (!function_exists('getCpuUsage')) {
  function getCpuUsage(): float
  {
    $stat1 = getCpuStats();
    sleep(1); // or usleep(500000) for 0.5 sec
    $stat2 = getCpuStats();

    $diff = [];
    foreach ($stat1 as $key => $value) {
      $diff[$key] = $stat2[$key] - $stat1[$key];
    }

    $total = array_sum($diff);
    $idle = $diff['idle'] + $diff['iowait'];

    return round((1 - ($idle / $total)) * 100, 2);
  }
}
if (!function_exists('getCpuStats')) {
  function getCpuStats(): array
  {
    $data = file_get_contents('/proc/stat');
    preg_match('/^cpu\s+(.+)/', $data, $matches);
    $parts = preg_split('/\s+/', trim($matches[1]));

    return [
      'user' => (int) $parts[0],
      'nice' => (int) $parts[1],
      'system' => (int) $parts[2],
      'idle' => (int) $parts[3],
      'iowait' => (int) $parts[4],
      'irq' => (int) $parts[5],
      'softirq' => (int) $parts[6],
      'steal' => (int) $parts[7],
      'guest' => (int) $parts[8] ?? 0,
      'guest_nice' => (int) $parts[9] ?? 0,
    ];
  }
}
if (!function_exists('sectionName')) {
  function sectionName($section)
  {
    $sectionNames = [
      'header' => __('Header'),
      'sidebar' => __('Side'),
      'content' => __('Content'),
      'footer' => __('Bottom'),
      'header-left' => __('Top left'),
      'header-middle' => __('Top middle'),
      'header-right' => __('Top right'),
      'middle-left' => __('Middle left'),
      'middle-right' => __('Middle right'),
      'footer-left' => __('Bottom left'),
      'footer-middle' => __('Bottom middle'),
      'footer-right' => __('Bottom right'),
    ];
    return isset($sectionNames[$section->value]) ? $sectionNames[$section->value] : $section->value;
  }
}
if (!function_exists('sectionNameBySlug')) {
  function sectionNameBySlug($section)
  {
    $sectionNames = [
      'header' => __('Header'),
      'sidebar' => __('Side'),
      'content' => __('Content'),
      'footer' => __('Bottom'),
      'header-left' => __('Top left'),
      'header-middle' => __('Top middle'),
      'header-right' => __('Top right'),
      'middle-left' => __('Middle left'),
      'middle-right' => __('Middle right'),
      'footer-left' => __('Bottom left'),
      'footer-middle' => __('Bottom middle'),
      'footer-right' => __('Bottom right'),
    ];
    return isset($sectionNames[$section]) ? $sectionNames[$section] : $section;
  }
}

if (!function_exists('isAdmin')) {
  function isAdmin()
  {
    if (auth() && auth()->user() && auth()->user()->isAdmin()) {
      return true;
    }
    return false;
  }
}



if (!function_exists('freePlanTemplateCount')) {
  function freePlanTemplateCount($key = '', $default = null)
  {
    return Template::where('plan_id', null)->where('active', true)->count();
  }
}

if (!function_exists('setting')) {
  function setting($key = '', $default = null)
  {
    return Setting::get($key, $default) ?? null;
  }
}


if (!function_exists('is_female')) {
  function is_female($fullname = '')
  {
    $fullname = trim($fullname);
    if (empty($fullname)) {
      return false;
    }
    if (str_ends_with($fullname, 'ova') || str_ends_with($fullname, 'eva')) {
      return true;
    }

    $nameArr = explode(' ', $fullname);
    $firstname = $nameArr[0];

    if (str_ends_with($firstname, 'ova') || str_ends_with($firstname, 'eva')) {
      return true;
    }
    $filename = resource_path('data') . '/female-names.php';
    if (file_exists($filename)) {
      $names = include $filename;
      if (in_array($firstname, $names)) {
        return true;
      }
    }
    return false;
  }
}

if (!function_exists('navigations')) {
  function navigations()
  {
    return [
      'profile' => [
        'name' => __('Profile'),
        'route' => 'profile',
        'icon' => 'icons.account',
      ],
      'skills' => [
        'name' => __('Skills'),
        'route' => 'skills',
        'icon' => 'icons.brain',
      ],
      'languages' => [
        'name' => __('Languages'),
        'route' => 'languages',
        'icon' => 'icons.languages',
      ],
      'experiences' => [
        'name' => __('Work Experience'),
        'route' => 'experiences',
        'icon' => 'icons.briefcase',
      ],
      'educations' => [
        'name' => __('Education'),
        'route' => 'educations',
        'icon' => 'icons.cap',
      ],
      'socials' => [
        'name' => __('Socials'),
        'route' => 'socials',
        'icon' => 'icons.comment',
      ],
      'projects' => [
        'name' => __('Projects'),
        'route' => 'projects',
        'icon' => 'icons.bulb',
      ],
      'awards' => [
        'name' => __('Awards'),
        'route' => 'awards',
        'icon' => 'icons.cup',
      ],
      'certificates' => [
        'name' => __('Certificates'),
        'route' => 'certificates',
        'icon' => 'icons.certificate',
      ],
      'hobbies' => [
        'name' => __('Hobbies'),
        'route' => 'hobbies',
        'icon' => 'icons.camera',
      ],
      'references' => [
        'name' => __('References'),
        'route' => 'references',
        'icon' => 'icons.reference',
      ],
    ];
  }
}

function priceWithCurrency($cents = 0)
{
  return config('site.currencySymbol') . ($cents / 100);
}

function priceAZN($qepik = 0)
{
  return '₼' . ($qepik / 100);
}

function priceUSD($cent = 0)
{
  return '$' . ($cent / 100);
}

function priceAZNLong($qepik = 0)
{
  return ($qepik / 100) . config('site.currencySymbol') . ' ' . __('Azerbaijani manat');
}



// function currentLanguage()
// {
//   return App::getLocale();
// }

function shuffle_assoc($array)
{
  if (!is_array($array))
    return $array;
  $keys = array_keys($array);

  shuffle($keys);

  foreach ($keys as $key) {
    $new[$key] = $array[$key];
  }

  return $new;

}

if (!function_exists('lroute')) {
  function lroute(string $name, array $params = [])
  {
    if (app()->getLocale() == 'en') {
      return route($name, array_merge([], $params));
    } else {
      return route($name, array_merge(['locale' => app()->getLocale()], $params));
    }
  }
}

if (!function_exists('cl')) {
  function cl($text = '')
  {
    $config = ['HTML.Allowed' => 'h1,h2,h3,h4,h5,h6,b,strong,i,em,u,p[style],br,span[style],a[href|title|target|rel],ul[style],ol[style],li,img[width|height|alt|src],blockquote'];

    return Purify::config($config)->clean($text);
  }
}

if (!function_exists('purlimit')) {
  function purlimit($text = '', $limit = 1024)
  {
    $config = ['HTML.Allowed' => ''];

    return Str::limit(Purify::config($config)->clean($text), $limit);
  }
}



if (!function_exists('cf_country')) {
  function cf_country()
  {
    if (!empty($_SERVER['HTTP_CF_IPCOUNTRY'])) {
      return strtolower($_SERVER['HTTP_CF_IPCOUNTRY']);
    }
    return '';
  }
}



if (!function_exists('is_google_bot')) {
  function is_google_bot()
  {
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $userAgent = strtolower($userAgent);
    if (strpos($userAgent, 'googlebot') !== false) {
      return true;
    }
    return false;
  }
}


if (!function_exists('countryNameByCode')) {
  function countryNameByCode($code)
  {
    $countries = [
      'AF' => 'Afghanistan',
      'AL' => 'Albania',
      'DZ' => 'Algeria',
      'AS' => 'American Samoa',
      'AD' => 'Andorra',
      'AO' => 'Angola',
      'AI' => 'Anguilla',
      'AQ' => 'Antarctica',
      'AG' => 'Antigua and Barbuda',
      'AR' => 'Argentina',
      'AM' => 'Armenia',
      'AW' => 'Aruba',
      'AU' => 'Australia',
      'AT' => 'Austria',
      'AZ' => 'Azerbaijan',
      'BS' => 'Bahamas',
      'BH' => 'Bahrain',
      'BD' => 'Bangladesh',
      'BB' => 'Barbados',
      'BY' => 'Belarus',
      'BE' => 'Belgium',
      'BZ' => 'Belize',
      'BJ' => 'Benin',
      'BM' => 'Bermuda',
      'BT' => 'Bhutan',
      'BO' => 'Bolivia',
      'BA' => 'Bosnia and Herzegovina',
      'BW' => 'Botswana',
      'BR' => 'Brazil',
      'IO' => 'British Indian Ocean Territory',
      'VG' => 'British Virgin Islands',
      'BN' => 'Brunei',
      'BG' => 'Bulgaria',
      'BF' => 'Burkina Faso',
      'BI' => 'Burundi',
      'KH' => 'Cambodia',
      'CM' => 'Cameroon',
      'CA' => 'Canada',
      'CV' => 'Cape Verde',
      'KY' => 'Cayman Islands',
      'CF' => 'Central African Republic',
      'TD' => 'Chad',
      'CL' => 'Chile',
      'CN' => 'China',
      'CX' => 'Christmas Island',
      'CC' => 'Cocos Islands',
      'CO' => 'Colombia',
      'KM' => 'Comoros',
      'CK' => 'Cook Islands',
      'CR' => 'Costa Rica',
      'HR' => 'Croatia',
      'CU' => 'Cuba',
      'CW' => 'Curacao',
      'CY' => 'Cyprus',
      'CZ' => 'Czech Republic',
      'CD' => 'Democratic Republic of the Congo',
      'DK' => 'Denmark',
      'DJ' => 'Djibouti',
      'DM' => 'Dominica',
      'DO' => 'Dominican Republic',
      'TL' => 'East Timor',
      'EC' => 'Ecuador',
      'EG' => 'Egypt',
      'SV' => 'El Salvador',
      'GQ' => 'Equatorial Guinea',
      'ER' => 'Eritrea',
      'EE' => 'Estonia',
      'ET' => 'Ethiopia',
      'FK' => 'Falkland Islands',
      'FO' => 'Faroe Islands',
      'FJ' => 'Fiji',
      'FI' => 'Finland',
      'FR' => 'France',
      'PF' => 'French Polynesia',
      'GA' => 'Gabon',
      'GM' => 'Gambia',
      'GE' => 'Georgia',
      'DE' => 'Germany',
      'GH' => 'Ghana',
      'GI' => 'Gibraltar',
      'GR' => 'Greece',
      'GL' => 'Greenland',
      'GD' => 'Grenada',
      'GU' => 'Guam',
      'GT' => 'Guatemala',
      'GG' => 'Guernsey',
      'GN' => 'Guinea',
      'GW' => 'Guinea-Bissau',
      'GY' => 'Guyana',
      'HT' => 'Haiti',
      'HN' => 'Honduras',
      'HK' => 'Hong Kong',
      'HU' => 'Hungary',
      'IS' => 'Iceland',
      'IN' => 'India',
      'ID' => 'Indonesia',
      'IR' => 'Iran',
      'IQ' => 'Iraq',
      'IE' => 'Ireland',
      'IM' => 'Isle of Man',
      'IL' => 'Israel',
      'IT' => 'Italy',
      'CI' => 'Ivory Coast',
      'JM' => 'Jamaica',
      'JP' => 'Japan',
      'JE' => 'Jersey',
      'JO' => 'Jordan',
      'KZ' => 'Kazakhstan',
      'KE' => 'Kenya',
      'KI' => 'Kiribati',
      'XK' => 'Kosovo',
      'KW' => 'Kuwait',
      'KG' => 'Kyrgyzstan',
      'LA' => 'Laos',
      'LV' => 'Latvia',
      'LB' => 'Lebanon',
      'LS' => 'Lesotho',
      'LR' => 'Liberia',
      'LY' => 'Libya',
      'LI' => 'Liechtenstein',
      'LT' => 'Lithuania',
      'LU' => 'Luxembourg',
      'MO' => 'Macau',
      'MK' => 'Macedonia',
      'MG' => 'Madagascar',
      'MW' => 'Malawi',
      'MY' => 'Malaysia',
      'MV' => 'Maldives',
      'ML' => 'Mali',
      'MT' => 'Malta',
      'MH' => 'Marshall Islands',
      'MR' => 'Mauritania',
      'MU' => 'Mauritius',
      'YT' => 'Mayotte',
      'MX' => 'Mexico',
      'FM' => 'Micronesia',
      'MD' => 'Moldova',
      'MC' => 'Monaco',
      'MN' => 'Mongolia',
      'ME' => 'Montenegro',
      'MS' => 'Montserrat',
      'MA' => 'Morocco',
      'MZ' => 'Mozambique',
      'MM' => 'Myanmar',
      'NA' => 'Namibia',
      'NR' => 'Nauru',
      'NP' => 'Nepal',
      'NL' => 'Netherlands',
      'AN' => 'Netherlands Antilles',
      'NC' => 'New Caledonia',
      'NZ' => 'New Zealand',
      'NI' => 'Nicaragua',
      'NE' => 'Niger',
      'NG' => 'Nigeria',
      'NU' => 'Niue',
      'KP' => 'North Korea',
      'MP' => 'Northern Mariana Islands',
      'NO' => 'Norway',
      'OM' => 'Oman',
      'PK' => 'Pakistan',
      'PW' => 'Palau',
      'PS' => 'Palestine',
      'PA' => 'Panama',
      'PG' => 'Papua New Guinea',
      'PY' => 'Paraguay',
      'PE' => 'Peru',
      'PH' => 'Philippines',
      'PN' => 'Pitcairn',
      'PL' => 'Poland',
      'PT' => 'Portugal',
      'PR' => 'Puerto Rico',
      'QA' => 'Qatar',
      'CG' => 'Republic of the Congo',
      'RE' => 'Reunion',
      'RO' => 'Romania',
      'RU' => 'Russia',
      'RW' => 'Rwanda',
      'BL' => 'Saint Barthelemy',
      'SH' => 'Saint Helena',
      'KN' => 'Saint Kitts and Nevis',
      'LC' => 'Saint Lucia',
      'MF' => 'Saint Martin',
      'PM' => 'Saint Pierre and Miquelon',
      'VC' => 'Saint Vincent and the Grenadines',
      'WS' => 'Samoa',
      'SM' => 'San Marino',
      'ST' => 'Sao Tome and Principe',
      'SA' => 'Saudi Arabia',
      'SN' => 'Senegal',
      'RS' => 'Serbia',
      'SC' => 'Seychelles',
      'SL' => 'Sierra Leone',
      'SG' => 'Singapore',
      'SX' => 'Sint Maarten',
      'SK' => 'Slovakia',
      'SI' => 'Slovenia',
      'SB' => 'Solomon Islands',
      'SO' => 'Somalia',
      'ZA' => 'South Africa',
      'KR' => 'South Korea',
      'SS' => 'South Sudan',
      'ES' => 'Spain',
      'LK' => 'Sri Lanka',
      'SD' => 'Sudan',
      'SR' => 'Suriname',
      'SJ' => 'Svalbard and Jan Mayen',
      'SZ' => 'Swaziland',
      'SE' => 'Sweden',
      'CH' => 'Switzerland',
      'SY' => 'Syria',
      'TW' => 'Taiwan',
      'TJ' => 'Tajikistan',
      'TZ' => 'Tanzania',
      'TH' => 'Thailand',
      'TG' => 'Togo',
      'TK' => 'Tokelau',
      'TO' => 'Tonga',
      'TT' => 'Trinidad and Tobago',
      'TN' => 'Tunisia',
      'TR' => 'Turkey',
      'TM' => 'Turkmenistan',
      'TC' => 'Turks and Caicos Islands',
      'TV' => 'Tuvalu',
      'VI' => 'U.S. Virgin Islands',
      'UG' => 'Uganda',
      'UA' => 'Ukraine',
      'AE' => 'United Arab Emirates',
      'GB' => 'United Kingdom',
      'US' => 'United States',
      'UY' => 'Uruguay',
      'UZ' => 'Uzbekistan',
      'VU' => 'Vanuatu',
      'VA' => 'Vatican',
      'VE' => 'Venezuela',
      'VN' => 'Vietnam',
      'WF' => 'Wallis and Futuna',
      'EH' => 'Western Sahara',
      'YE' => 'Yemen',
      'ZM' => 'Zambia',
      'ZW' => 'Zimbabwe',
      'XX' => 'No Country',
      'T1' => 'Tor network'
    ];

    if (isset($countries[$code])) {
      return $countries[$code];
    }

    return $code;

  }
}





if (!function_exists('is_bot')) {
  function is_bot($userAgent)
  {
    if ($userAgent == '') {
      return true;
    }
    $bots = array(
      'bot',
      'crawl',
      'slurp',
      'spider',
      'mediapartners',
      'google',
      'bingpreview',
      'facebookexternalhit',
      'linkedinbot',
      'embedly',
      'pageburst',
      'Twitterbot',
      'quora link preview',
      'outbrain',
      'pinterest',
      'Pinterestbot',
      'developers.google.com/+/web/snippet',
      'NetAPI'
    );

    $agent = strtolower($userAgent);

    foreach ($bots as $bot) {
      if (strpos($agent, $bot) !== false) {
        return true;
      }
    }

    return false;
  }
}

if (!function_exists('uuid_token')) {
  function uuid_token()
  {
    return Illuminate\Support\Str::uuid();
  }
}

if (!function_exists('strip_domain_name')) {
  function strip_domain_name($url = '')
  {
    $host = parse_url($url, PHP_URL_HOST);
    if ($host === false || $host === null) {
      $host = $url;
    }
    // 2. Remove protocol prefixes (http://, https://) if they weren't removed by parse_url
    // or if the original string was just a domain with protocol.
    $cleanedHost = preg_replace('/^https?:\/\//i', '', $host);

    // 3. Remove 'www.' prefix (case-insensitive)
    $cleanedHost = preg_replace('/^www\./i', '', $cleanedHost);
    $parts = explode('.', $cleanedHost);
    $numParts = count($parts);
    if ($numParts > 0) {
      return $parts[0] . '.' . $parts[$numParts - 1];
    }
    return $url;

  }
}
if (!function_exists('formatDuration')) {
  function formatDuration(int $totalSeconds): string
  {
    if ($totalSeconds < 0) {
      return "Invalid duration (negative seconds)";
    }

    if ($totalSeconds === 0) {
      return "0 seconds";
    }

    $hours = floor($totalSeconds / 3600);
    $remainingSecondsAfterHours = $totalSeconds % 3600;

    $minutes = floor($remainingSecondsAfterHours / 60);
    $seconds = $remainingSecondsAfterHours % 60;

    $parts = [];

    if ($hours > 0) {
      $parts[] = $hours . ' ' . ($hours === 1 ? 'h' : 'h');
    }

    if ($minutes > 0 || ($hours > 0 && $seconds === 0)) { // Include minutes if there are hours and no seconds left
      $parts[] = $minutes . ' ' . ($minutes === 1 ? 'm' : 'm');
    }

    // Only show seconds if there are no hours/minutes, or if there are remaining seconds
    if ($seconds > 0 || (empty($parts) && $totalSeconds < 60)) {
      $parts[] = $seconds . ' ' . ($seconds === 1 ? 's' : 's');
    }

    // If only hours and minutes are present, and seconds is 0, ensure seconds part is not added unless it's the only unit.
    // This logic is refined by the conditions above.

    // If the duration is exactly 1 hour or 1 minute, and seconds is 0, the previous logic might still add "0 seconds".
    // Let's refine the output for cleaner display.
    if ($hours > 0 && $minutes === 0 && $seconds === 0) {
      return $hours . ' ' . ($hours === 1 ? 'hour' : 'hours');
    }
    if ($minutes > 0 && $seconds === 0 && $hours === 0) {
      return $minutes . ' ' . ($minutes === 1 ? 'minute' : 'minutes');
    }
    if ($seconds > 0 && $minutes === 0 && $hours === 0) {
      return $seconds . ' ' . ($seconds === 1 ? 'second' : 'seconds');
    }


    return implode(' ', $parts);
  }
}

if (!function_exists('blockName')) {
  function blockName($name = "")
  {
    return ucfirst(str_replace('-', ' ', str_replace('_', ' ', $name)));
  }
}

if (!function_exists('updateBlackListedIps')) {
  function updateBlackListedIps()
  {
    $blackListedIpsFile = 'blacklisted-ips.txt';
    $original_file_path = Storage::disk('local')->path($blackListedIpsFile);
    file_put_contents($original_file_path, '');
    $ips = Blacklist::all()->pluck('ip')->unique();
    foreach ($ips as $key => $ip) {
      file_put_contents($original_file_path, $ip . PHP_EOL, FILE_APPEND);
    }
  }
}

if (!function_exists('getBlacklistedIps')) {
  function getBlacklistedIps()
  {
    $blackListedIpsFile = 'blacklisted-ips.txt';
    $original_file_path = Storage::disk('local')->path($blackListedIpsFile);
    if (is_file($original_file_path)) {
      return file_get_contents($original_file_path);
    }
    return '';
  }
}

if (!function_exists('isVisitorBlocked')) {
  function isVisitorBlockedByIp($ip = "")
  {
    if (empty($ip)) {
      return false;
    }
    $fileContent = getBlacklistedIps();
    $lines = explode(PHP_EOL, $fileContent);
    return in_array($ip, $lines);
  }
}


if (!function_exists('geminiAnalyzeResumePDF')) {
  function geminiAnalyzeResumePDF($resumeText, $schemaContent)
  {
    $apiKey = config('site.geminiAPIKey');

    // Step 1: Upload the PDF file to Gemini using resumable upload
    // $uploadUrl = 'https://generativelanguage.googleapis.com/upload/v1beta/files?key=' . $apiKey;

    // $fileContent = file_get_contents($pdfPath);
    // $fileName = basename($pdfPath);
    // $fileSize = strlen($fileContent);

    // // Start resumable upload session
    // $metadata = [
    //   'file' => [
    //     'display_name' => $fileName
    //   ]
    // ];

    // $uploadResponse = Http::withHeaders([
    //   'X-Goog-Upload-Protocol' => 'resumable',
    //   'X-Goog-Upload-Command' => 'start',
    //   'X-Goog-Upload-Header-Content-Length' => $fileSize,
    //   'X-Goog-Upload-Header-Content-Type' => 'application/pdf',
    //   'Content-Type' => 'application/json',
    // ])->post($uploadUrl, $metadata);

    // if (!$uploadResponse->successful()) {
    //   return ['error' => 'Failed to start upload', 'details' => $uploadResponse->body()];
    // }

    // $uploadUrlFromHeader = $uploadResponse->header('X-Goog-Upload-URL');

    // if (!$uploadUrlFromHeader) {
    //   return ['error' => 'No upload URL returned', 'headers' => $uploadResponse->headers()];
    // }

    // Upload the actual file content
    // $uploadFileResponse = Http::withHeaders([
    //   'Content-Length' => $fileSize,
    //   'X-Goog-Upload-Offset' => '0',
    //   'X-Goog-Upload-Command' => 'upload, finalize',
    // ])->withBody($fileContent, 'application/pdf')
    //   ->post($uploadUrlFromHeader);

    // if (!$uploadFileResponse->successful()) {
    // return ['error' => 'Failed to upload PDF content', 'details' => $uploadFileResponse->body()];
    // }

    // $fileData = $uploadFileResponse->json();
    // $fileUri = $fileData['file']['uri'] ?? null;

    // if (!$fileUri) {
    //   dump($fileUri);
    //   return ['error' => 'No file URI returned'];
    // }

    // Step 2: Wait for file to be processed
    // sleep(2);

    // Step 3: Generate content with the uploaded file
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
            // [
            //   'file_data' => [
            //     'mime_type' => 'application/pdf',
            //     'file_uri' => $fileUri
            //   ]
            // ]
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

    return ['error' => 'Request failed', 'status' => $response->status(), 'body' => $response->body()];
  }
}
