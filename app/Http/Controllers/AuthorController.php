<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Author;

class AuthorController extends Controller
{
    // AuthorController.php



    public function muellifadd()
    {
        return view('Muellif/muellif-ad');
    }

    public function store(Request $request)
    {
        $request->validate([
            'companyName' => 'required',
            'email'       => 'required|email',
            'website'     => 'required'
        ]);

        Author::create([
            'name'  => $request->companyName,
            'email' => $request->email,
            'site'  => $request->website,
        ]);

        return redirect()->back()->with('success', 'Müəllif uğurla əlavə olundu!');
    }
}

