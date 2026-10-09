@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-4" x-data="{
    layout: '{{ old('layout_type', $page->layout_type) }}'
}">
    <!-- Breadcrumb & Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <a href="{{ route('admin.pages.index') }}" class="text-xs text-stone-500 hover:text-stone-800 uppercase tracking-widest font-semibold flex items-center gap-1 mb-1">
                &larr; Kembali ke Kelola Halaman
            </a>
            <h1 class="font-serif-diary italic text-2xl sm:text-3xl text-stone-800 font-medium">
                Edit Halaman {{ $page->page_number }}
            </h1>
        </div>
    </div>

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-lg text-sm">
            <div class="font-semibold mb-1">Terdapat kesalahan dalam mengisi form:</div>
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Container -->
    <form action="{{ route('admin.pages.update', $page->id) }}" method="POST" enctype="multipart/form-data" class="bg-white border border-[#E5DAC6] rounded-xl p-6 sm:p-8 shadow-xs space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Page Number -->
            <div>
                <label for="page_number" class="block text-xs font-semibold uppercase tracking-wider text-stone-600 mb-2">
                    Nomor Halaman <span class="text-rose-500">*</span>
                </label>
                <input type="number" name="page_number" id="page_number" min="1" value="{{ old('page_number', $page->page_number) }}" required 
                       class="w-full bg-[#FAF7F2] border border-[#E0D5C1] rounded-lg px-3.5 py-2.5 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-stone-400">
            </div>
        </div>

        <hr class="border-[#F0E6D6]">

        <!-- Audio & Song Section -->
        <div class="bg-[#FAF7F2] border border-[#ECE2D0] rounded-lg p-4 space-y-4">
            <h3 class="font-serif-diary italic text-lg text-stone-800 font-semibold flex items-center gap-2">
                <span class="text-rose-800">♪</span> Background Audio Track
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="song_title" class="block text-xs font-semibold text-stone-600 mb-1">Judul Lagu / Track</label>
                    <input type="text" name="song_title" id="song_title" placeholder="Contoh: Track 02: you! — LANY" value="{{ old('song_title', $page->song_title) }}"
                           class="w-full bg-white border border-[#E0D5C1] rounded-md px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-1 focus:ring-stone-400">
                </div>
                <div>
                    <label for="audio_file" class="block text-xs font-semibold text-stone-600 mb-1">File Audio (Kosongkan jika tidak diganti)</label>
                    <input type="file" name="audio_file" id="audio_file" accept="audio/*"
                           class="w-full text-xs text-stone-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-stone-800 file:text-white hover:file:bg-stone-900 cursor-pointer">
                    @if($page->audio_file)
                        <span class="text-[11px] text-emerald-700 mt-1 block">Audio saat ini: {{ basename($page->audio_file) }}</span>
                    @endif
                </div>
            </div>
        </div>

        <hr class="border-[#F0E6D6]">

        <!-- LAYOUT SELECTION DROPDOWN -->
        <div>
            <label for="layout_type" class="block text-xs font-semibold uppercase tracking-wider text-stone-700 mb-2">
                Pilihan Layout Halaman <span class="text-rose-500">*</span>
            </label>
            <select name="layout_type" id="layout_type" x-model="layout" required 
                    class="w-full bg-[#F4EFE6] border border-[#D9CEB9] font-medium rounded-lg px-4 py-3 text-sm text-stone-900 focus:outline-none focus:ring-2 focus:ring-stone-500 shadow-2xs">
                <option value="text_only">📝 text_only (Judul & Catatan / Lirik Lagu)</option>
                <option value="full_media">🖼️ full_media (1 Media Video/Foto Fullscreen + Caption)</option>
                <option value="photo_album_4">📸 photo_album_4 (Grid 2x2 Untuk 4 Foto + Caption)</option>
            </select>
        </div>

        <!-- DYNAMIC SECTION 1: LAYOUT 'text_only' -->
        <div x-show="layout === 'text_only'" x-transition class="space-y-4 bg-[#FFFDF9] border border-[#E8DFC8] p-5 rounded-lg">
            <div>
                <label for="note_title" class="block text-xs font-semibold text-stone-700 mb-1">Judul Notes / Catatan</label>
                <input type="text" name="note_title" id="note_title" placeholder="Contoh: TOMORROW N FOREVER" value="{{ old('note_title', $page->note_title) }}"
                       class="w-full bg-[#FAF7F2] border border-[#E0D5C1] rounded-md px-3.5 py-2.5 text-sm font-serif-diary uppercase tracking-widest text-stone-800 focus:outline-none focus:ring-1 focus:ring-stone-400">
            </div>

            <div>
                <label for="note_content" class="block text-xs font-semibold text-stone-700 mb-1">Isi Teks Catatan / Lirik Lagu</label>
                <textarea name="note_content" id="note_content" rows="10" 
                          class="w-full bg-[#FAF7F2] border border-[#E0D5C1] rounded-md p-3.5 text-sm font-serif-diary italic text-stone-800 leading-relaxed focus:outline-none focus:ring-1 focus:ring-stone-400 whitespace-pre-line">{{ old('note_content', $page->note_content) }}</textarea>
            </div>
        </div>

        <!-- DYNAMIC SECTION 2: LAYOUT 'full_media' -->
        <div x-show="layout === 'full_media'" x-transition class="space-y-4 bg-[#FFFDF9] border border-[#E8DFC8] p-5 rounded-lg" x-cloak>
            @php $fullItem = $page->items->firstWhere('position', 1) ?? $page->items->first(); @endphp

            @if($fullItem)
                <div class="mb-3">
                    <span class="text-xs text-stone-500 block mb-1">Media saat ini:</span>
                    <img src="{{ asset($fullItem->file_path) }}" alt="Preview" class="w-32 h-32 object-cover rounded border">
                </div>
            @endif

            <div>
                <label for="full_media_file" class="block text-xs font-semibold text-stone-700 mb-1">Ganti File Media (Kosongkan jika tidak diganti)</label>
                <input type="file" name="full_media_file" id="full_media_file" accept="image/*,video/*"
                       class="w-full text-xs text-stone-600 file:mr-3 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-stone-800 file:text-white hover:file:bg-stone-900 cursor-pointer">
            </div>

            <div>
                <label for="full_media_caption" class="block text-xs font-semibold text-stone-700 mb-1">Caption Media</label>
                <textarea name="full_media_caption" id="full_media_caption" rows="3"
                          class="w-full bg-[#FAF7F2] border border-[#E0D5C1] rounded-md p-3 text-sm font-serif-diary italic text-stone-800 focus:outline-none focus:ring-1 focus:ring-stone-400">{{ old('full_media_caption', $fullItem?->caption) }}</textarea>
            </div>
        </div>

        <!-- DYNAMIC SECTION 3: LAYOUT 'photo_album_4' -->
        <div x-show="layout === 'photo_album_4'" x-transition class="space-y-4 bg-[#FFFDF9] border border-[#E8DFC8] p-5 rounded-lg" x-cloak>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @for($pos = 1; $pos <= 4; $pos++)
                    @php $item = $page->items->firstWhere('position', $pos); @endphp
                    <div class="bg-[#FAF7F2] border border-[#EAE1D0] rounded-md p-3.5 space-y-2">
                        <span class="text-xs font-semibold uppercase tracking-wider text-stone-600 block">Posisi {{ $pos }}</span>

                        @if($item)
                            <div class="flex items-center gap-2 mb-1">
                                <img src="{{ asset($item->file_path) }}" alt="P{{$pos}}" class="w-10 h-10 object-cover rounded border">
                                <span class="text-[11px] text-stone-500 truncate">{{ basename($item->file_path) }}</span>
                            </div>
                        @endif

                        <div>
                            <input type="file" name="album_media_{{ $pos }}" accept="image/*,video/*"
                                   class="w-full text-xs text-stone-600 file:mr-2 file:py-1 file:px-2.5 file:rounded file:border-0 file:text-[11px] file:font-semibold file:bg-stone-700 file:text-white cursor-pointer">
                        </div>
                        <div>
                            <input type="text" name="album_caption_{{ $pos }}" placeholder="Caption Foto {{ $pos }}" value="{{ old('album_caption_'.$pos, $item?->caption) }}"
                                   class="w-full bg-white border border-[#E0D5C1] rounded px-2.5 py-1.5 text-xs font-serif-diary italic text-stone-800 focus:outline-none">
                        </div>
                    </div>
                @endfor
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-[#F0E6D6]">
            <a href="{{ route('admin.pages.index') }}" class="px-4 py-2.5 text-xs font-medium uppercase tracking-wider text-stone-600 hover:text-stone-900">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-lg bg-stone-900 text-white font-medium text-xs uppercase tracking-wider hover:bg-black transition-all shadow-sm">
                Perbarui Halaman
            </button>
        </div>
    </form>
</div>
@endsection
