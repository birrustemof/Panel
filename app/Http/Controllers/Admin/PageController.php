<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class PageController extends Controller
{
    public function index()
    {
        return view('dashboard.index');
    }

    public function index2()
    {
        return view('dashboard.index2');
    }

    public function index3()
    {
        return view('dashboard.index3');
    }
}
