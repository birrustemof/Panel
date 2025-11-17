<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{

    public function KatoqoriyaNew(): View
    {
        return view('tables.katoqoriya-new');
    }


    public function KatoqoriyaList(): View
    {
        $categories = Category::latest()->get();
        return view('tables.katoqoriya-list', compact('categories'));
    }


    public function create(): View
    {
        return view('tables.Katoqoriya-new');
    }

    /**
     * Yeni kateqoriyanı bazada saxlayır

     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name'
        ]);

        try {
            Category::create($validated);

            return redirect()
                ->route('katoqoriya.list')
                ->with('success', 'Kateqoriya uğurla əlavə edildi!');


        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Kateqoriya əlavə edilərkən xəta baş verdi: ' . $e->getMessage());
        }
    }


    public function index(): View
    {
        $categories = Category::withTrashed()->latest()->get();
        return view('tables.Katoqoriya-list', compact('categories'));
    }
}
