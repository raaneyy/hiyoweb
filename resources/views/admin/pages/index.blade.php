@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-4">

    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="font-serif-diary italic text-2xl sm:text-3xl text-stone-800 font-medium">
                Kelola Halaman Digital Diary
            </h1>
            <p class="text-xs text-stone-500 mt-1">Daftar seluruh halaman notebook pribadi Anda.</p>
        </div>

        <a href="{{ route('admin.pages.create', ['diary_id' => $selectedDiaryId]) }}" class="px-4 py-2.5 rounded-lg bg-stone-900 text-white font-medium text-xs uppercase tracking-wider hover:bg-black transition-all shadow-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Halaman Baru
        </a>
    </div>

    <!-- Pages List Table / Cards -->
    @if($pages->isEmpty())
        <div class="bg-white border border-[#E5DAC6] rounded-xl p-12 text-center">
            <svg class="w-12 h-12 text-stone-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
            <h3 class="font-serif-diary italic text-xl text-stone-600 mb-2">Belum ada halaman di diary ini</h3>
            <p class="text-xs text-stone-400 max-w-sm mx-auto mb-4">Mulai buat halaman pertama Anda dengan memilih layout Text Only, Full Media, atau Photo Album 4.</p>
            <a href="{{ route('admin.pages.create', ['diary_id' => $selectedDiaryId]) }}" class="inline-flex items-center gap-1 text-xs font-semibold uppercase tracking-wider text-stone-800 underline hover:text-black">
                + Tambah Halaman Sekarang
            </a>
        </div>
    @else
        <div class="bg-white border border-[#E5DAC6] rounded-xl overflow-hidden shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-[#FAF7F2] border-b border-[#E5DAC6] text-stone-600 font-semibold uppercase tracking-wider">
                        <tr>
                            <th class="py-3.5 px-4">Hlm #</th>
                            <th class="py-3.5 px-4">Tipe Layout</th>
                            <th class="py-3.5 px-4">Audio Track</th>
                            <th class="py-3.5 px-4">Ringkasan Konten</th>
                            <th class="py-3.5 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F0E6D6] text-stone-700">
                        @foreach($pages as $p)
                            <tr class="hover:bg-[#FFFDF9] transition-colors">
                                <td class="py-3.5 px-4 font-mono font-bold text-stone-900">
                                    Halaman {{ $p->page_number }}
                                </td>
                                <td class="py-3.5 px-4">
                                    @if($p->layout_type === 'text_only')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-amber-100 text-amber-800">
                                            📝 text_only
                                        </span>
                                    @elseif($p->layout_type === 'full_media')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-blue-100 text-blue-800">
                                            🖼️ full_media
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-emerald-100 text-emerald-800">
                                            📸 photo_album_4
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    @if($p->song_title)
                                        <div class="flex items-center gap-1 text-rose-800 font-medium">
                                            <span>♪</span> <span class="truncate max-w-[160px]">{{ $p->song_title }}</span>
                                        </div>
                                    @else
                                        <span class="text-stone-300">-</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 max-w-xs truncate">
                                    @if($p->layout_type === 'text_only')
                                        <span class="font-serif-diary italic font-semibold text-stone-800">{{ $p->note_title ?? 'Catatan' }}:</span>
                                        <span class="text-stone-500 font-serif-diary italic">{{ Str::limit($p->note_content, 40) }}</span>
                                    @else
                                        <span class="text-stone-500">{{ $p->items->count() }} media item(s)</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-right space-x-2">
                                    @php $spreadIndex = (int)ceil($p->page_number / 2); @endphp
                                    <a href="{{ route('diary.show', ['diary' => $p->diary_id, 'spread' => $spreadIndex]) }}" 
                                       class="text-xs font-medium text-stone-600 hover:text-stone-900 underline">
                                        Lihat Halaman
                                    </a>
                                    <a href="{{ route('admin.pages.edit', $p->id) }}" 
                                       class="text-xs font-medium text-amber-700 hover:text-amber-900 underline">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.pages.destroy', $p->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus halaman ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-medium text-rose-600 hover:text-rose-900 underline">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>
@endsection
