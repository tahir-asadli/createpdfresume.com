<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;

class ResumeController extends Controller
{
    public function __invoke()
    {
        return view('dashboard.resume');
    }
}
