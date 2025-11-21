<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class MuellifController extends Controller
{

    public function muelliflist()
    {
        $authors = Author::all(); // Məlumatları çək
        return view('Muellif/muellif-list', compact('authors'));
    }


}
