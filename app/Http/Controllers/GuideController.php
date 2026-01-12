<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuideController extends Controller
{
    /**
     * Display the system usage guide (SOP).
     */
    public function index()
    {
        return view('guide.index');
    }
}
