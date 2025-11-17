<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News; // News modelini əlavə edin

class PageController extends Controller
{
    public function formsGeneral()
    {
        return view('forms.general');
    }

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



    public function xeber2()
    {
        $news = News::orderBy('created_at', 'desc')->paginate(10);
        return view('xeber2', compact('news'));
    }



}
