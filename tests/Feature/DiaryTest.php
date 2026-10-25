<?php

namespace Tests\Feature;

use App\Models\Diary;
use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DiaryTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_render_landing_page(): void
    {
        $response = $this->get(route('landing'));
        $response->assertStatus(200);
        $response->assertSee('Kalau kata kamuuuu');
        $response->assertSee('OPEN');
    }

    public function test_can_render_verification_page(): void
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);
        $response->assertSee('Verifikasi');
        $response->assertSee('AWAL MARET');
    }

    public function test_reader_verification_success_redirects_to_diary(): void
    {
        $diary = Diary::create(['title' => 'Test Diary']);

        $response = $this->post(route('login.reader'), [
            'hari' => 18,
            'bulan' => 3,
            'tahun' => 2026,
        ]);

        $response->assertRedirect(route('diary.show', $diary->id));
        $this->assertTrue(session('is_reader_verified'));
    }

    public function test_can_view_diary_flipbook_pages_when_verified(): void
    {
        $diary = Diary::create([
            'title' => 'Test Diary Notebook',
            'description' => 'Test description',
        ]);

        $page1 = Page::create([
            'diary_id' => $diary->id,
            'page_number' => 1,
            'layout_type' => 'text_only',
            'note_title' => 'TOMORROW N FOREVER',
            'note_content' => 'You are the one I needed this whole time.',
        ]);

        $response = $this->withSession(['is_reader_verified' => true])
            ->get(route('diary.show', $diary->id));

        $response->assertStatus(200);
        $response->assertSee('TOMORROW N FOREVER');
        $response->assertSee('You are the one I needed this whole time.');
    }

    public function test_can_render_admin_create_page_form(): void
    {
        $response = $this->withSession(['is_admin' => true])
            ->get(route('admin.pages.create'));

        $response->assertStatus(200);
        $response->assertSee('Tambah Halaman Baru');
        $response->assertSee('text_only');
        $response->assertSee('full_media');
        $response->assertSee('photo_album_4');
    }

    public function test_can_create_text_only_page_via_admin(): void
    {
        $diary = Diary::create([
            'title' => 'Test Diary',
        ]);

        $response = $this->withSession(['is_admin' => true])
            ->post(route('admin.pages.store'), [
                'diary_id' => $diary->id,
                'page_number' => 1,
                'layout_type' => 'text_only',
                'note_title' => 'MY MEMORY TITLE',
                'note_content' => 'Special diary note content.',
                'song_title' => 'Track 01: song',
            ]);

        $response->assertRedirect(route('admin.pages.index', ['diary_id' => $diary->id]));
        $this->assertDatabaseHas('pages', [
            'diary_id' => $diary->id,
            'page_number' => 1,
            'layout_type' => 'text_only',
            'note_title' => 'MY MEMORY TITLE',
            'note_content' => 'Special diary note content.',
            'song_title' => 'Track 01: song',
        ]);
    }

    public function test_can_create_full_media_page_via_admin(): void
    {
        Storage::fake('public');

        $diary = Diary::create(['title' => 'Test Diary']);
        $file = UploadedFile::fake()->image('photo.jpg');

        $response = $this->withSession(['is_admin' => true])
            ->post(route('admin.pages.store'), [
                'diary_id' => $diary->id,
                'page_number' => 2,
                'layout_type' => 'full_media',
                'full_media_file' => $file,
                'full_media_caption' => 'Love u moree, sayangg.',
            ]);

        $response->assertRedirect(route('admin.pages.index', ['diary_id' => $diary->id]));
        $this->assertDatabaseHas('pages', [
            'diary_id' => $diary->id,
            'page_number' => 2,
            'layout_type' => 'full_media',
        ]);
        $this->assertDatabaseHas('page_items', [
            'position' => 1,
            'caption' => 'Love u moree, sayangg.',
        ]);
    }

    public function test_can_create_page_with_auto_page_number(): void
    {
        $diary = Diary::create(['title' => 'Test Diary']);

        // First page auto assigned to 1
        $this->withSession(['is_admin' => true])
            ->post(route('admin.pages.store'), [
                'diary_id' => $diary->id,
                'layout_type' => 'text_only',
                'note_title' => 'Page 1',
            ]);

        $this->assertDatabaseHas('pages', [
            'diary_id' => $diary->id,
            'page_number' => 1,
            'note_title' => 'Page 1',
        ]);

        // Second page auto assigned to 2
        $this->withSession(['is_admin' => true])
            ->post(route('admin.pages.store'), [
                'diary_id' => $diary->id,
                'layout_type' => 'text_only',
                'note_title' => 'Page 2',
            ]);

        $this->assertDatabaseHas('pages', [
            'diary_id' => $diary->id,
            'page_number' => 2,
            'note_title' => 'Page 2',
        ]);
    }
}
