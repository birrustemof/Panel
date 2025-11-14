<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;

class NewsController extends Controller
{
    public function news(){
        $news = News::all();
        return view('tables.simple', compact('news'));
    }

    public function show($id)
    {
        $newsItem = News::findOrFail($id);
        return view('forms.general', compact('newsItem'));
    }

    // YENİ: Xəbər yaratmaq üçün
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|min:15|max:255',
            'text' => 'required|string|min:10'
        ]);

        News::create([
            'title' => $request->title,
            'text' => $request->text
        ]);

        return redirect()->route('xeber2')
            ->with('success', 'Xəbər uğurla əlavə edildi!');
    }

    public function update(Request $request, $id)
    {
        $newsItem = News::findOrFail($id);

        $request->validate([
            'title' => 'required|string|min:15|max:255',
            'text' => 'required|string|min:10'
        ]);

        $newsItem->update([
            'title' => $request->title,
            'text' => $request->text
        ]);

        return redirect()->route('forms.general', $id)
            ->with('success', 'Xəbər uğurla yeniləndi!');
    }

    // YENİ: Xəbər silmək üçün
    public function destroy($id)
    {
        $newsItem = News::findOrFail($id);
        $newsItem->delete();

        return redirect()->route('xeber2')
            ->with('success', 'Xəbər uğurla silindi!');
    }


}
