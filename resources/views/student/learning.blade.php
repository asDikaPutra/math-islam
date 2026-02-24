<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $course->title }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- TAMBAHKAN INI: Alert Error --}}
            @if(session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-md shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        {{-- Icon X-Circle --}}
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700 font-medium">
                            {{ session('error') }}
                        </p>
                    </div>
                </div>
            </div>
            @endif

            {{-- Alert Success (Opsional, biar sekalian ada) --}}
            @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-md shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700 font-medium">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <div class="py-6">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="flex flex-col lg:flex-row gap-6">

                        {{-- KIRI: KONTEN UTAMA (VIDEO/TEKS) --}}
                        <div class="flex-1">
                            @if($currentLesson)
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                                {{-- Video Player --}}
                                @if($currentLesson->type == 'video' && $currentLesson->video_url)
                                <div class="aspect-video bg-black w-full relative">
                                    <iframe id="youtube-player" class="w-full h-full"
                                        src="https://www.youtube.com/embed/{{ \Str::afterLast($currentLesson->video_url, 'v=') }}?enablejsapi=1&rel=0"
                                        frameborder="0" allow="autoplay; encrypted-media" allowfullscreen>
                                    </iframe>
                                </div>
                                @endif

                                <div class="p-6">
                                    <div class="flex justify-between items-start mb-4">
                                        <h1 class="text-2xl font-bold text-gray-900">{{ $currentLesson->title }}</h1>
                                        <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                            {{ $currentLesson->duration_minutes }} Menit
                                        </span>
                                    </div>

                                    <div class="prose max-w-none text-gray-600">
                                        {!! $currentLesson->content !!}
                                    </div>

                                    {{-- Navigasi Next --}}
                                    <div class="mt-8 flex justify-end border-t pt-4">
                                        <form action="{{ route('learning.complete', $currentLesson->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md text-sm font-medium transition-colors">
                                                Tandai Selesai & Lanjut &rarr;
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="bg-white p-6 rounded-lg shadow-sm text-center">
                                <p class="text-gray-500">Belum ada materi di kursus ini.</p>
                            </div>
                            @endif
                        </div>

                        {{-- KANAN: SIDEBAR DAFTAR MATERI --}}
                        {{-- KANAN: SIDEBAR DAFTAR MATERI --}}
                        <div class="w-full lg:w-80 shrink-0">
                            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200 overflow-hidden sticky top-24">
                                <div class="p-4 bg-gray-50 border-b border-gray-200">
                                    <h3 class="font-bold text-gray-900">Daftar Materi</h3>
                                </div>

                                <div class="max-h-[calc(100vh-200px)] overflow-y-auto">
                                    {{-- LOGIKA: Materi pertama dianggap terbuka (true) --}}
                                    @php $isPreviousLessonCompleted = true; @endphp

                                    @foreach($course->modules as $module)
                                    <div>
                                        {{-- Judul Modul --}}
                                        <div class="px-4 py-2 bg-gray-100 text-xs font-bold text-gray-500 uppercase tracking-wider">
                                            {{ $module->title }}
                                        </div>

                                        {{-- List Lesson --}}
                                        @foreach($module->lessons as $lesson)
                                        @php
                                        $isActive = $currentLesson && $currentLesson->id == $lesson->id;
                                        $isCompleted = in_array($lesson->id, $completedLessonIds);

                                        // LOGIKA KUNCI:
                                        // Lesson ini TERKUNCI jika lesson sebelumnya BELUM SELESAI
                                        // (Kecuali lesson ini sendiri sudah selesai)
                                        $isLocked = !$isPreviousLessonCompleted && !$isCompleted;
                                        @endphp

                                        {{-- Tentukan Link: Jika terkunci, matikan link (href="#") --}}
                                        <a href="{{ $isLocked ? '#' : route('learning.show', [$course->slug, $lesson->slug]) }}"
                                            class="block px-4 py-3 border-b border-gray-100 transition-colors 
                           {{ $isActive ? 'bg-indigo-50 border-l-4 border-l-indigo-600' : ($isLocked ? 'bg-gray-50 cursor-not-allowed opacity-75' : 'hover:bg-gray-50') }}">

                                            <div class="flex items-center gap-3">
                                                {{-- LOGIKA IKON --}}
                                                @if($isCompleted)
                                                {{-- 1. Selesai (Checklist Hijau) --}}
                                                <div class="text-green-500 shrink-0">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </div>
                                                @elseif($isActive)
                                                {{-- 2. Sedang Diputar (Play Biru) --}}
                                                <div class="text-indigo-500 shrink-0">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </div>
                                                @elseif($isLocked)
                                                {{-- 3. Terkunci (Gembok Abu) --}}
                                                <div class="text-gray-400 shrink-0">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                                    </svg>
                                                </div>
                                                @else
                                                {{-- 4. Terbuka/Tersedia tapi belum mulai (Lingkaran Play Abu) --}}
                                                <div class="text-gray-400 shrink-0">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </div>
                                                @endif

                                                <span class="text-sm {{ $isActive ? 'font-semibold text-indigo-700' : ($isLocked ? 'text-gray-400' : 'text-gray-700') }}">
                                                    {{ $lesson->title }}
                                                </span>
                                            </div>
                                        </a>

                                        {{-- UPDATE STATUS UNTUK ITERASI BERIKUTNYA --}}
                                        {{-- Jika lesson INI selesai, maka lesson BERIKUTNYA boleh terbuka --}}
                                        @php
                                        $isPreviousLessonCompleted = $isCompleted;
                                        @endphp

                                        @endforeach
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
</x-app-layout>

@push('scripts')
@if($currentLesson->type == 'video')
<script src="https://www.youtube.com/iframe_api"></script>
<script>
    var player;

    // 1. Cek apakah API YouTube berhasil dimuat
    function onYouTubeIframeAPIReady() {
        console.log("✅ API YouTube Siap!");

        // Binding ke iframe dengan ID 'youtube-player'
        player = new YT.Player('youtube-player', {
            events: {
                'onReady': onPlayerReady,
                'onStateChange': onPlayerStateChange
            }
        });
    }

    // 2. Cek apakah Player siap
    function onPlayerReady(event) {
        console.log("✅ Player Siap & Terhubung!");
    }

    // 3. Cek perubahan status (Play, Pause, Selesai)
    function onPlayerStateChange(event) {
        console.log("ℹ️ Status Video Berubah: " + event.data);

        // Status 0 = ENDED (Selesai)
        if (event.data === 0) {
            console.log("🎉 Video Selesai! Memulai proses simpan...");
            markLessonAsComplete();
        }
    }

    function markLessonAsComplete() {
        console.log("⏳ Mengirim request ke Laravel...");

        fetch("{{ route('learning.complete', $currentLesson->id) }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                },
                body: JSON.stringify({})
            })
            .then(response => {
                console.log("📩 Respon Server:", response.status);
                if (response.ok) {
                    console.log("✅ Sukses! Refreshing halaman...");
                    window.location.reload();
                } else {
                    console.error("❌ Gagal menyimpan. Status:", response.status);
                    alert("Gagal menyimpan progres. Coba refresh halaman.");
                }
            })
            .catch(error => {
                console.error('❌ Error Fetch:', error);
            });
    }
</script>
@endif
@endpush