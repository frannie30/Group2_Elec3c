<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Show the public Our Mission page.
     *
     * Accessible by both guests and authenticated users (no auth middleware).
     */
    public function mission()
    {
        return view('mission');
    }
}
