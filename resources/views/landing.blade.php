<!DOCTYPE html>
<html lang="id" class="h-full bg-[#FDF9F1]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Diary & Interactive Notebook</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,500;1,600&family=Plus+Jakarta+Sans:wght@400;500;600&family=Playfair+Display:ital,wght@0,400;0,600;1,400;1,600&display=swap" rel="stylesheet">
    <style>
        .font-serif-diary {
            font-family: 'Cormorant Garamond', 'Playfair Display', Georgia, serif;
        }
    </style>
</head>
<body class="min-h-screen bg-[#FDF9F1] flex flex-col justify-between items-center p-4 sm:p-8 selection:bg-[#EADBCE]">

    <!-- LANDING PAGE CENTER CARD (MATCHING USER REFERENCE IMAGE 1) -->
    <div class="my-auto w-full max-w-3xl px-2">
        <div class="bg-[#F3EFE6]/60 p-3 sm:p-5 rounded-2xl border border-[#EBE3D3] shadow-md">
            <div class="bg-white rounded-xl py-16 sm:py-24 px-6 sm:px-12 text-center border border-[#EAE3D4] shadow-2xs flex flex-col items-center justify-center space-y-8">
                
                <div class="space-y-3 font-serif-diary italic text-stone-700 leading-relaxed">
                    <p class="text-xl sm:text-2xl md:text-3xl font-medium tracking-wide">
                        Kalau kata kamuuuu,
                    </p>
                    <p class="text-xl sm:text-2xl md:text-3xl font-medium tracking-wide">
                        “No matter how far we are, my heart is always for u”
                    </p>
                </div>

                <div class="pt-4">
                    <a href="{{ route('login') }}" 
                       class="inline-flex items-center gap-2 bg-stone-950 hover:bg-stone-800 text-white font-sans text-xs font-bold uppercase tracking-[0.25em] px-8 py-3.5 rounded-sm shadow-sm transition-all transform hover:scale-105 active:scale-95">
                        OPEN &rarr;
                    </a>
                </div>

            </div>
        </div>
    </div>

</body>
</html>
