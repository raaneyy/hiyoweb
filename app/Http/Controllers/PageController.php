<?php

namespace App\Http\Controllers;

use App\Models\Diary;
use App\Models\Page;
use App\Models\PageItem;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $diaries = Diary::all();
        $selectedDiaryId = $request->query('diary_id', $diaries->first()?->id);

        $pages = Page::with('items')
            ->when($selectedDiaryId, fn ($q) => $q->where('diary_id', $selectedDiaryId))
            ->orderBy('page_number')
            ->get();

        return view('admin.pages.index', compact('pages', 'diaries', 'selectedDiaryId'));
    }

    public function create(Request $request)
    {
        $diaries = Diary::all();

        // If no diary exists, create a default one
        if ($diaries->isEmpty()) {
            $diary = Diary::create([
                'title' => 'My Journal',
                'description' => 'A cozy interactive flipbook notebook.',
                'cover_color' => '#2D2824',
            ]);
            $diaries = collect([$diary]);
        }

        $selectedDiaryId = $request->query('diary_id', $diaries->first()->id);
        $suggestedPageNum = (Page::where('diary_id', $selectedDiaryId)->max('page_number') ?? 0) + 1;

        return view('admin.pages.create', compact('diaries', 'selectedDiaryId', 'suggestedPageNum'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'diary_id' => 'nullable|exists:diaries,id',
            'page_number' => 'nullable|integer|min:1',
            'song_title' => 'nullable|string|max:255',
            'audio_file' => 'nullable|file|mimes:mp3,wav,m4a,ogg,aac|max:20480',
            'layout_type' => 'required|in:full_media,photo_album_4,text_only',
            'note_title' => 'nullable|string|max:255',
            'note_content' => 'nullable|string',
        ]);

        $diaryId = $validated['diary_id'] ?? Diary::first()?->id;

        if (! $diaryId) {
            $diary = Diary::create([
                'title' => 'My Journal',
                'description' => 'A cozy digital flipbook notebook for memories and notes.',
                'cover_color' => '#2D2824',
            ]);
            $diaryId = $diary->id;
        }

        $pageNumber = ! empty($validated['page_number'])
            ? (int) $validated['page_number']
            : ((Page::where('diary_id', $diaryId)->max('page_number') ?? 0) + 1);

        // Check if page_number already exists for this diary and auto bump if needed
        $existingPage = Page::where('diary_id', $diaryId)
            ->where('page_number', $pageNumber)
            ->first();

        if ($existingPage) {
            $pageNumber = (Page::where('diary_id', $diaryId)->max('page_number') ?? 0) + 1;
        }

        $audioPath = null;
        if ($request->hasFile('audio_file')) {
            $audioPath = 'storage/'.$request->file('audio_file')->store('audio', 'public');
        }

        $page = Page::create([
            'diary_id' => $diaryId,
            'page_number' => $pageNumber,
            'song_title' => $validated['song_title'] ?? null,
            'audio_file' => $audioPath,
            'layout_type' => $validated['layout_type'],
            'note_title' => $validated['note_title'] ?? null,
            'note_content' => $validated['note_content'] ?? null,
        ]);

        // Process items based on layout type
        if ($validated['layout_type'] === 'full_media') {
            if ($request->hasFile('full_media_file')) {
                $file = $request->file('full_media_file');
                $path = 'storage/'.$file->store('pages', 'public');
                $mime = $file->getMimeType();
                $mediaType = str_starts_with($mime, 'video/') ? 'video' : 'image';

                PageItem::create([
                    'page_id' => $page->id,
                    'media_type' => $mediaType,
                    'file_path' => $path,
                    'caption' => $request->input('full_media_caption'),
                    'position' => 1,
                ]);
            }
        } elseif ($validated['layout_type'] === 'photo_album_4') {
            for ($pos = 1; $pos <= 4; $pos++) {
                $fileKey = "album_media_$pos";
                $captionKey = "album_caption_$pos";

                if ($request->hasFile($fileKey)) {
                    $file = $request->file($fileKey);
                    $path = 'storage/'.$file->store('pages', 'public');
                    $mime = $file->getMimeType();
                    $mediaType = str_starts_with($mime, 'video/') ? 'video' : 'image';

                    PageItem::create([
                        'page_id' => $page->id,
                        'media_type' => $mediaType,
                        'file_path' => $path,
                        'caption' => $request->input($captionKey),
                        'position' => $pos,
                    ]);
                }
            }
        }

        return redirect()->route('admin.pages.index', ['diary_id' => $page->diary_id])
            ->with('success', 'Halaman berhasil ditambahkan ke Digital Diary!');
    }

    public function edit(Page $page)
    {
        $page->load('items');
        $diaries = Diary::all();

        return view('admin.pages.edit', compact('page', 'diaries'));
    }

    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'diary_id' => 'required|exists:diaries,id',
            'page_number' => 'required|integer|min:1',
            'song_title' => 'nullable|string|max:255',
            'audio_file' => 'nullable|file|mimes:mp3,wav,m4a,ogg,aac|max:20480',
            'layout_type' => 'required|in:full_media,photo_album_4,text_only',
            'note_title' => 'nullable|string|max:255',
            'note_content' => 'nullable|string',
        ]);

        // Unique check
        $existingPage = Page::where('diary_id', $validated['diary_id'])
            ->where('page_number', $validated['page_number'])
            ->where('id', '!=', $page->id)
            ->first();

        if ($existingPage) {
            return back()->withInput()->withErrors(['page_number' => 'Halaman nomor '.$validated['page_number'].' sudah ada di diary ini.']);
        }

        if ($request->hasFile('audio_file')) {
            $validated['audio_file'] = 'storage/'.$request->file('audio_file')->store('audio', 'public');
        } else {
            unset($validated['audio_file']);
        }

        $page->update($validated);

        // Update items if layout is full_media or photo_album_4
        if ($validated['layout_type'] === 'full_media') {
            if ($request->hasFile('full_media_file')) {
                $file = $request->file('full_media_file');
                $path = 'storage/'.$file->store('pages', 'public');
                $mime = $file->getMimeType();
                $mediaType = str_starts_with($mime, 'video/') ? 'video' : 'image';

                PageItem::updateOrCreate(
                    ['page_id' => $page->id, 'position' => 1],
                    [
                        'media_type' => $mediaType,
                        'file_path' => $path,
                        'caption' => $request->input('full_media_caption'),
                    ]
                );
            } elseif ($request->filled('full_media_caption')) {
                $item = $page->items->firstWhere('position', 1);
                if ($item) {
                    $item->update(['caption' => $request->input('full_media_caption')]);
                }
            }
        } elseif ($validated['layout_type'] === 'photo_album_4') {
            for ($pos = 1; $pos <= 4; $pos++) {
                $fileKey = "album_media_$pos";
                $captionKey = "album_caption_$pos";

                $item = $page->items->firstWhere('position', $pos);

                if ($request->hasFile($fileKey)) {
                    $file = $request->file($fileKey);
                    $path = 'storage/'.$file->store('pages', 'public');
                    $mime = $file->getMimeType();
                    $mediaType = str_starts_with($mime, 'video/') ? 'video' : 'image';

                    PageItem::updateOrCreate(
                        ['page_id' => $page->id, 'position' => $pos],
                        [
                            'media_type' => $mediaType,
                            'file_path' => $path,
                            'caption' => $request->input($captionKey),
                        ]
                    );
                } elseif ($item && $request->has($captionKey)) {
                    $item->update(['caption' => $request->input($captionKey)]);
                }
            }
        }

        return redirect()->route('admin.pages.index', ['diary_id' => $page->diary_id])
            ->with('success', 'Halaman berhasil diperbarui!');
    }

    public function destroy(Page $page)
    {
        $diaryId = $page->diary_id;
        $page->delete();

        return redirect()->route('admin.pages.index', ['diary_id' => $diaryId])
            ->with('success', 'Halaman telah dihapus.');
    }
}
