<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    public function news(Request $request)
    {
        $search = $request->search;

        $news = News::latest()
            ->when($search, function($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('text', 'like', "%{$search}%")
                        ->orWhere('author', 'like', "%{$search}%");
                });
            })
            ->paginate(10)
            ->appends($request->only('search'));

        return view('tables.simple', compact('news', 'search'));
    }

    public function show($id)
    {
        $newsItem = News::findOrFail($id);
        return view('forms.general', compact('newsItem'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => [
                'required',
                'string',
                'min:5',
                'max:15',
                function ($attribute, $value, $fail) {
                    if (preg_match('/(edumediya|edumedia|Edumediya|Edumedia)/i', $value)) {
                        $fail('Xəbər adında "Edumediya" aid sözlər ola bilməz!');
                    }
                }
            ],
            'text' => [
                'required',
                'string',
                'min:10',
                'max:50'
            ],
            'author' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'title.min' => 'Title ən az 5 xarakter olmalıdır',
            'title.max' => 'Title ən çox 15 xarakter olmalıdır',
            'text.min' => 'Text ən az 10 xarakter olmalıdır',
            'text.max' => 'Text ən çox 50 xarakter olmalıdır'
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('news-images', 'public');
        }

        $news = News::create([
            'title' => $request->title,
            'text' => $request->text,
            'author' => $request->author,
            'image' => $imagePath
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Xəbər uğurla əlavə edildi!',
                'news' => [
                    'id' => $news->id,
                    'title' => $news->title,
                    'text' => $news->text,
                    'image' => $news->image,
                    'created_at' => $news->created_at
                ]
            ]);
        }

        return redirect()->route('xeber2', ['page' => $request->get('page', 1)])
            ->with('success', 'Xəbər uğurla əlavə edildi!');
    }

    public function update(Request $request, $id)
    {
        $newsItem = News::findOrFail($id);

        $request->validate([
            'title' => [
                'required',
                'string',
                'min:5',
                'max:15',
                function ($attribute, $value, $fail) {
                    if (preg_match('/(edumediya|edumedia|Edumediya|Edumedia)/i', $value)) {
                        $fail('Xəbər adında "Edumediya" aid sözlər ola bilməz!');
                    }
                }
            ],
            'text' => [
                'required',
                'string',
                'min:10',
                'max:50'
            ],
            'author' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'title.min' => 'Title ən az 5 xarakter olmalıdır',
            'title.max' => 'Title ən çox 15 xarakter olmalıdır',
            'text.min' => 'Text ən az 10 xarakter olmalıdır',
            'text.max' => 'Text ən çox 50 xarakter olmalıdır'
        ]);

        // Əlavə manual yoxlama (təhlükəsizlik üçün)
        if (preg_match('/(edumediya|edumedia|Edumediya|Edumedia)/i', $request->title)) {
            return back()->withErrors([
                'title' => 'Xəbər adında "Edumediya" aid sözlər ola bilməz!'
            ]);
        }

        $data = [
            'title' => $request->title,
            'text' => $request->text,
            'author' => $request->author
        ];

        // Şəkil silmə əməliyyatı
        if ($request->has('remove_image') && $request->remove_image == '1') {
            // Köhnə şəkili sil
            if ($newsItem->image) {
                Storage::disk('public')->delete($newsItem->image);
            }
            $data['image'] = null;
        }
        // Yeni şəkil yükləmə
        else if ($request->hasFile('image')) {
            // Köhnə şəkili sil
            if ($newsItem->image) {
                Storage::disk('public')->delete($newsItem->image);
            }
            // Yeni şəkili yüklə
            $data['image'] = $request->file('image')->store('news-images', 'public');
        }

        $newsItem->update($data);

        return redirect()->route('xeber2')
            ->with('success', 'Xəbər uğurla yeniləndi!');
    }

    // Silmə metodu - SOFT DELETE
    public function destroy($id)
    {
        $news = News::findOrFail($id);

        // SOFT DELETE - şəkili silmirik, sadəcə deleted_at əlavə edirik
        $news->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Xəbər uğurla silindi!'
            ]);
        }

        return redirect()->route('xeber2')
            ->with('success', 'Xəbər uğurla silindi!');
    }

    public function deletedNews()
    {
        $deletedNews = News::onlyTrashed()->orderBy('deleted_at', 'desc')->get();
        return view('tables.deleted-news', compact('deletedNews'));
    }

    // Xəbəri bərpa et - DÜZƏLİŞ EDİLDİ
    public function restore($id)
    {
        $news = News::onlyTrashed()->findOrFail($id);
        $news->restore();

        return redirect()->route('news.deleted') // DÜZƏLDİ: 'deleted.news' -> 'news.deleted'
        ->with('success', 'Xəbər uğurla bərpa edildi!');
    }

    public function forceDelete($id)
    {
        try {
            $news = News::onlyTrashed()->findOrFail($id);

            // Şəkili sil
            if ($news->image) {
                Storage::disk('public')->delete($news->image);
            }

            $news->forceDelete();

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Xəbər həqiqətən silindi!'
                ]);
            }

            return redirect()->route('news.deleted')
                ->with('success', 'Xəbər həqiqətən silindi!');

        } catch (\Exception $e) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Xəta: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('news.deleted')
                ->with('error', 'Xəta baş verdi: ' . $e->getMessage());
        }
    }

    // Excel export metodu
    public function export()
    {
        $news = News::all();
        $filename = 'xeberler_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($news) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM əlavə et (Excel üçün)
            fwrite($file, "\xEF\xBB\xBF");

            // Başlıqlar
            fputcsv($file, ['ID', 'Başlıq', 'Müəllif', 'Mətn', 'Şəkil', 'Yaradılma Tarixi', 'Yenilənmə Tarixi']);

            // Məlumatlar
            foreach ($news as $item) {
                fputcsv($file, [
                    $item->id,
                    $item->title,
                    $item->author,
                    $item->text,
                    $item->image ? 'Var' : 'Yoxdur',
                    $item->created_at->format('d.m.Y H:i'),
                    $item->updated_at->format('d.m.Y H:i')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Xeber2 səhifəsi üçün metod
    public function xeber2(Request $request)
    {
        $news = News::orderBy('created_at', 'desc')->paginate(10);
        return view('xeber2', compact('news'));
    }

    // Forms general səhifəsi üçün metod
    public function formsGeneral($id)
    {
        $newsItem = News::findOrFail($id);
        return view('forms.general', compact('newsItem'));
    }

    // Forms general update üçün metod
    public function formsGeneralUpdate(Request $request, $id)
    {
        return $this->update($request, $id);
    }



}
