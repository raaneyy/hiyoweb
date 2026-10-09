{{-- Partial Page Content Renderer --}}
<div class="w-full flex flex-col justify-start h-full">

    <!-- Audio Player Bar (If song_title or audio_file is provided) -->
    @if($page->song_title || $page->audio_file)
        @php $audioId = 'audio_page_' . $page->id; @endphp
        <div class="bg-[#F5F0E6] rounded-lg px-4 py-2.5 flex items-center justify-between border border-[#ECE5D8] mb-5 shadow-2xs">
            <div class="flex items-center gap-2 text-stone-800 font-medium text-xs sm:text-sm tracking-tight truncate">
                <span class="text-rose-800 text-base">♪</span>
                <span class="truncate font-sans font-medium">{{ $page->song_title ?? 'Background Track' }}</span>
            </div>

            @if($page->audio_file)
                <audio id="{{ $audioId }}" src="{{ asset($page->audio_file) }}" preload="none"></audio>
                <button type="button" 
                        @click="playAudio('{{ $audioId }}')"
                        class="w-7 h-7 rounded-full bg-stone-900 text-white flex items-center justify-center hover:bg-stone-800 active:scale-95 transition-all shadow-2xs shrink-0">
                    <svg x-show="activeAudio !== document.getElementById('{{ $audioId }}')" class="w-3.5 h-3.5 ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8 5v14l11-7z"/>
                    </svg>
                    <svg x-show="activeAudio === document.getElementById('{{ $audioId }}')" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24" x-cloak>
                        <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
                    </svg>
                </button>
            @else
                <!-- Demo fallback play button if audio file is not uploaded yet -->
                <div class="w-7 h-7 rounded-full bg-stone-900 text-white flex items-center justify-center shrink-0">
                    <svg class="w-3.5 h-3.5 ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8 5v14l11-7z"/>
                    </svg>
                </div>
            @endif
        </div>
    @endif


    <!-- 1. TEXT ONLY LAYOUT (Notes / Lyrics) -->
    @if($page->layout_type === 'text_only')
        <div class="flex flex-col items-center justify-center my-auto py-2">
            @if($page->note_title)
                <h2 class="font-serif-diary italic uppercase tracking-[0.25em] text-stone-700 text-center font-medium text-base sm:text-lg mb-4 sm:mb-6">
                    {{ $page->note_title }}
                </h2>
            @endif

            @if($page->note_content)
                <div class="bg-[#F7F3E9] p-6 sm:p-8 rounded-sm border border-[#ECE5D8] w-full text-center font-serif-diary italic text-stone-700 text-sm sm:text-base leading-relaxed tracking-wide shadow-2xs whitespace-pre-line">
{{ $page->note_content }}
                </div>
            @endif
        </div>


    <!-- 2. FULL MEDIA LAYOUT -->
    @elseif($page->layout_type === 'full_media')
        <div class="flex flex-col items-center justify-center my-auto w-full">
            @php $item = $page->items->firstWhere('position', 1) ?? $page->items->first(); @endphp

            @if($item)
                <div class="w-full bg-[#F4EFE6] p-1.5 rounded-sm border border-[#ECE5D8] shadow-2xs">
                    @if($item->media_type === 'video' || Str::endsWith($item->file_path, ['.mp4', '.webm', '.mov']))
                        <video controls class="w-full max-h-[420px] object-cover rounded-2xs">
                            <source src="{{ asset($item->file_path) }}">
                            Browser Anda tidak mendukung tag video.
                        </video>
                    @else
                        <img src="{{ asset($item->file_path) }}" alt="Page Media" class="w-full max-h-[420px] object-cover rounded-2xs">
                    @endif
                </div>

                @if($item->caption)
                    <div class="font-serif-diary italic text-stone-700 text-sm sm:text-base text-center mt-4 px-2 leading-relaxed whitespace-pre-line">
                        {{ $item->caption }}
                    </div>
                @endif
            @else
                <!-- Placeholder if no media item uploaded yet -->
                <div class="w-full h-80 bg-stone-200/60 rounded-sm border border-dashed border-stone-300 flex flex-col items-center justify-center text-stone-400">
                    <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="font-serif-diary italic text-stone-500">Full Media Placeholder</span>
                </div>
            @endif
        </div>


    <!-- 3. PHOTO ALBUM 4 GRID LAYOUT -->
    @elseif($page->layout_type === 'photo_album_4')
        <div class="grid grid-cols-2 gap-3.5 my-auto w-full">
            @for($pos = 1; $pos <= 4; $pos++)
                @php $item = $page->items->firstWhere('position', $pos); @endphp

                <div class="flex flex-col justify-start">
                    @if($item)
                        <div class="bg-[#F4EFE6] p-1 rounded-sm border border-[#ECE5D8] shadow-2xs">
                            @if($item->media_type === 'video' || Str::endsWith($item->file_path, ['.mp4', '.webm', '.mov']))
                                <video controls class="w-full h-32 sm:h-40 object-cover rounded-2xs">
                                    <source src="{{ asset($item->file_path) }}">
                                </video>
                            @else
                                <img src="{{ asset($item->file_path) }}" alt="Photo {{ $pos }}" class="w-full h-32 sm:h-40 object-cover rounded-2xs">
                            @endif
                        </div>

                        @if($item->caption)
                            <div class="font-serif-diary italic text-stone-700 text-xs sm:text-sm mt-1.5 px-0.5 leading-snug whitespace-pre-line">
                                {{ $item->caption }}
                            </div>
                        @endif
                    @else
                        <!-- Empty photo slot -->
                        <div class="bg-[#F4EFE6]/70 border border-dashed border-[#E5DAC6] rounded-sm h-32 sm:h-40 flex items-center justify-center text-stone-400">
                            <span class="font-serif-diary italic text-xs text-stone-400">note {{ $pos }}</span>
                        </div>
                    @endif
                </div>
            @endfor
        </div>
    @endif

</div>
