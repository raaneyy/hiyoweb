<?php

namespace Database\Seeders;

use App\Models\Diary;
use App\Models\Page;
use App\Models\PageItem;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $diary = Diary::create([
            'title' => 'My Journal',
            'description' => 'A cozy digital flipbook notebook for memories and notes.',
            'cover_color' => '#2D2824',
        ]);

        // Page 1: Photo Album 4 (Track 01)
        $p1 = Page::create([
            'diary_id' => $diary->id,
            'page_number' => 1,
            'song_title' => 'Track 01: song',
            'layout_type' => 'photo_album_4',
        ]);
        PageItem::create(['page_id' => $p1->id, 'media_type' => 'image', 'file_path' => 'storage/pages/demo1.svg', 'caption' => 'note', 'position' => 1]);
        PageItem::create(['page_id' => $p1->id, 'media_type' => 'image', 'file_path' => 'storage/pages/demo2.svg', 'caption' => 'note', 'position' => 2]);
        PageItem::create(['page_id' => $p1->id, 'media_type' => 'image', 'file_path' => 'storage/pages/demo3.svg', 'caption' => "DATE\nnote", 'position' => 3]);
        PageItem::create(['page_id' => $p1->id, 'media_type' => 'image', 'file_path' => 'storage/pages/demo4.svg', 'caption' => 'note', 'position' => 4]);

        // Page 2: Photo Album 4 (Track 02)
        $p2 = Page::create([
            'diary_id' => $diary->id,
            'page_number' => 2,
            'song_title' => 'Track 02: song',
            'layout_type' => 'photo_album_4',
        ]);
        PageItem::create(['page_id' => $p2->id, 'media_type' => 'image', 'file_path' => 'storage/pages/demo1.svg', 'caption' => 'note', 'position' => 1]);
        PageItem::create(['page_id' => $p2->id, 'media_type' => 'image', 'file_path' => 'storage/pages/demo2.svg', 'caption' => 'note', 'position' => 2]);
        PageItem::create(['page_id' => $p2->id, 'media_type' => 'image', 'file_path' => 'storage/pages/demo3.svg', 'caption' => 'note', 'position' => 3]);
        PageItem::create(['page_id' => $p2->id, 'media_type' => 'image', 'file_path' => 'storage/pages/demo4.svg', 'caption' => 'note', 'position' => 4]);

        // Page 3: Full Media (Track 02: you! — LANY)
        $p3 = Page::create([
            'diary_id' => $diary->id,
            'page_number' => 3,
            'song_title' => 'Track 02: you! — LANY',
            'layout_type' => 'full_media',
        ]);
        PageItem::create([
            'page_id' => $p3->id,
            'media_type' => 'image',
            'file_path' => 'storage/pages/full_demo.svg',
            'caption' => "You're the one I needed this whole time\n\nlove u moree, sayangg.",
            'position' => 1,
        ]);

        // Page 4: Photo Album 4 (Track 02: you! — LANY)
        $p4 = Page::create([
            'diary_id' => $diary->id,
            'page_number' => 4,
            'song_title' => 'Track 02: you! — LANY',
            'layout_type' => 'photo_album_4',
        ]);
        PageItem::create(['page_id' => $p4->id, 'media_type' => 'image', 'file_path' => 'storage/pages/demo1.svg', 'caption' => 'GANTENG 1', 'position' => 1]);
        PageItem::create(['page_id' => $p4->id, 'media_type' => 'image', 'file_path' => 'storage/pages/demo2.svg', 'caption' => 'GANTENG 2', 'position' => 2]);
        PageItem::create(['page_id' => $p4->id, 'media_type' => 'image', 'file_path' => 'storage/pages/demo3.svg', 'caption' => 'ini adalah salah satu hal yang bakal aku ingat seumur hidup aku. bunga, surat, photobooth hahaha. sedih sebenarnya karna bakalan ldr. huwaaaa nangisss lagiiiiiii', 'position' => 3]);
        PageItem::create(['page_id' => $p4->id, 'media_type' => 'image', 'file_path' => 'storage/pages/demo4.svg', 'caption' => "You're the one I needed this whole time\n\nlove u moree, sayangg.", 'position' => 4]);

        // Page 5: Text Only (TOMORROW N FOREVER)
        $p5 = Page::create([
            'diary_id' => $diary->id,
            'page_number' => 5,
            'layout_type' => 'text_only',
            'note_title' => 'TOMORROW N FOREVER',
            'note_content' => "You're the one I needed this whole time\nYou're the one I needed this whole time\nYou're the one I needed this whole time\nYou're the one I needed this whole time\nYou're the one I needed this whole time\nYou're the one I needed this whole time\nYou're the one I needed this whole time\nYou're the one I needed this whole time\nYou're the one I needed this whole time\nYou're the one I needed this whole time\nYou're the one I needed this whole time\nYou're the one I needed this whole time\nYou're the one I needed this whole time\nYou're the one I needed this whole time\nYou're the one I needed this whole time\nYou're the one I needed this whole time\nYou're the one I needed this whole time\nYou're the one I needed this whole time\nYou're the one I needed this whole time\nYou're the one I needed this whole time\nYou're the one I needed this whole time\nYou're the one I needed this whole time\nLOVE YOYS",
        ]);

        // Page 6: Text Only (MEMORIES & DREAMS)
        $p6 = Page::create([
            'diary_id' => $diary->id,
            'page_number' => 6,
            'layout_type' => 'text_only',
            'note_title' => 'MEMORIES & DREAMS',
            'note_content' => "Every single day spent with you is my favorite day.\nSo, today is my new favorite day.\n\nThank you for being in my life.\nForever and always.",
        ]);
    }
}
