@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-2 sm:px-4" x-data="{
    activeAudio: null,
    playAudio(id) {
        let audioEl = document.getElementById(id);
        if (!audioEl) return;
        if (this.activeAudio && this.activeAudio !== audioEl) {
            this.activeAudio.pause();
        }
        if (audioEl.paused) {
            audioEl.play();
            this.activeAudio = audioEl;
        } else {
            audioEl.pause();
            this.activeAudio = null;
        }
    }
}">

    <!-- Diary Header / Selector -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-6 px-2">
        <div class="flex items-center gap-3">
            <h1 class="font-serif-diary italic text-2xl md:text-3xl text-stone-800 font-medium">
                {{ $diary->title }}
            </h1>
            @if($allDiaries->count() > 1)
                <select onchange="location = this.value" class="text-xs bg-white border border-[#E7DEC8] rounded-md px-2.5 py-1 text-stone-700 focus:outline-none focus:ring-1 focus:ring-stone-400">
                    @foreach($allDiaries as $d)
                        <option value="{{ route('diary.show', $d->id) }}" {{ $d->id == $diary->id ? 'selected' : '' }}>
                            {{ $d->title }}
                        </option>
                    @endforeach
                </select>
            @endif
        </div>

    </div>

    <!-- PHYSICAL BOOK SPREAD WRAPPER (DOUBLE PAGE LAYOUT) -->
    <div class="relative bg-[#F9F6F0] p-3 sm:p-6 rounded-2xl border border-[#E8DFC8] book-container-shadow transition-all duration-300">
        
        <!-- Book Outer Container (2 Pages Side-by-Side) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-0 bg-[#FFFDF9] rounded-xl overflow-hidden border border-[#E5DAC6] relative min-h-[580px] md:min-h-[680px]">
            
            <!-- CENTRAL BOOK SPINE GUTTER SHADOW -->
            <div class="hidden md:block absolute top-0 bottom-0 left-1/2 -ml-3 w-6 z-30 pointer-events-none book-spine-shadow opacity-90"></div>

            <!-- ================= LEFT PAGE ================= -->
            <div class="p-6 sm:p-8 flex flex-col justify-between border-b md:border-b-0 md:border-r border-[#EBE1D0] left-page-shadow relative bg-[#FFFDF9] min-h-[560px]">
                
                <!-- Left Page Header -->
                <div class="flex items-center justify-between mb-4">
                    <span class="text-[10px] sm:text-xs font-semibold uppercase tracking-widest text-stone-400 font-sans">
                        @if($spread > 1)
                            <a href="{{ route('diary.show', ['diary' => $diary->id, 'spread' => $spread - 1]) }}" class="hover:text-stone-700 transition-colors">
                                PREVIOUS
                            </a>
                        @else
                            &nbsp;
                        @endif
                    </span>
                    <span class="text-[10px] font-mono text-stone-300">HLM {{ $leftPageNum }}</span>
                </div>

                <!-- Left Page Main Content -->
                <div class="flex-grow flex flex-col justify-start">
                    @if($leftPage)
                        @include('diary.partials.page_content', ['page' => $leftPage, 'side' => 'left'])
                    @else
                        <!-- Blank / Empty Page State -->
                        <div class="flex-grow flex flex-col items-center justify-center text-center p-8 text-stone-300">
                            <svg class="w-12 h-12 mb-3 stroke-[1.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            <p class="font-serif-diary italic text-stone-400 text-lg mb-2">Halaman {{ $leftPageNum }} Belum Diisi</p>
                            <a href="{{ route('admin.pages.create', ['diary_id' => $diary->id, 'page_number' => $leftPageNum]) }}" class="text-xs text-stone-500 hover:text-stone-800 underline font-sans uppercase tracking-wider">
                                + Isi Halaman Ini
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Left Page Footer -->
                <div class="mt-4 pt-2 border-t border-[#F2EBDE] flex justify-between items-center text-[10px] text-stone-400 font-mono">
                    <span>{{ $diary->title }}</span>
                    <span>{{ $leftPageNum }}</span>
                </div>
            </div>

            <!-- ================= RIGHT PAGE ================= -->
            <div class="p-6 sm:p-8 flex flex-col justify-between right-page-shadow relative bg-[#FFFDF9] min-h-[560px]">
                
                <!-- Right Page Header -->
                <div class="flex items-center justify-between mb-4">
                    <span class="text-[10px] font-mono text-stone-300">HLM {{ $rightPageNum }}</span>
                    <span class="text-[10px] sm:text-xs font-semibold uppercase tracking-widest text-stone-400 font-sans">
                        @if($spread < $totalSpreads)
                            <a href="{{ route('diary.show', ['diary' => $diary->id, 'spread' => $spread + 1]) }}" class="hover:text-stone-700 transition-colors">
                                NEXT PAGE
                            </a>
                        @else
                            &nbsp;
                        @endif
                    </span>
                </div>

                <!-- Right Page Main Content -->
                <div class="flex-grow flex flex-col justify-start">
                    @if($rightPage)
                        @include('diary.partials.page_content', ['page' => $rightPage, 'side' => 'right'])
                    @else
                        <!-- Blank / Empty Page State -->
                        <div class="flex-grow flex flex-col items-center justify-center text-center p-8 text-stone-300">
                            <svg class="w-12 h-12 mb-3 stroke-[1.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            <p class="font-serif-diary italic text-stone-400 text-lg mb-2">Halaman {{ $rightPageNum }} Belum Diisi</p>
                            <a href="{{ route('admin.pages.create', ['diary_id' => $diary->id, 'page_number' => $rightPageNum]) }}" class="text-xs text-stone-500 hover:text-stone-800 underline font-sans uppercase tracking-wider">
                                + Isi Halaman Ini
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Right Page Footer -->
                <div class="mt-4 pt-2 border-t border-[#F2EBDE] flex justify-between items-center text-[10px] text-stone-400 font-mono">
                    <span>{{ $rightPageNum }}</span>
                    <span>{{ $diary->title }}</span>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
