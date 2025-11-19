<x-app-layout>
    {{-- SLOT HEADER: Judul Halaman (Opsional) --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    {{-- SLOT UTAMA: Isi Dashboard --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="space-y-6">

                {{-- 1. HEADER SECTION --}}
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 tracking-tight">
                        Selamat Datang, {{ $user->name }}! 👋
                    </h1>
                    <p class="text-gray-500 mt-2 text-lg">
                        NIM: <span class="font-medium text-gray-700">{{ $user?->nim ?? '-' }}</span>
                    </p>
                </div>
            
                {{-- 2. ALERT: FIRST LOGIN --}}
                {{-- Hanya muncul jika is_first_login = true di database --}}
                @if($user->profile?->is_first_login)
                <div class="rounded-lg border border-indigo-200 bg-indigo-50 p-4 shadow-sm" role="alert">
                    <div class="flex items-center gap-4">
                        <div class="p-2 bg-white rounded-full border border-indigo-100">
                            {{-- Icon Info --}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-indigo-600"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                        </div>
                        <div>
                            <h5 class="font-semibold text-indigo-900">Selamat Bergabung!</h5>
                            <p class="text-sm text-indigo-700 mt-1">
                                Ini adalah login pertama Anda. Silakan 
                                {{-- Pastikan route profile.edit ada, atau ganti '#' --}}
                                <a href="{{ route('profile.edit') }}" class="underline hover:text-indigo-900">lengkapi profil Anda</a> 
                                dan jelajahi kursus yang tersedia.
                            </p>
                        </div>
                    </div>
                </div>
                @endif
            
                {{-- 3. STATS CARDS GRID --}}
                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
            
                    {{-- Card 1: Kursus Diikuti --}}
                    <div class="rounded-xl border border-gray-200 bg-white text-gray-950 shadow-sm hover:shadow-md transition-all p-6">
                        <div class="flex flex-row items-center justify-between space-y-0 pb-2">
                            <h3 class="tracking-tight text-sm font-medium text-gray-500">Kursus Diikuti</h3>
                            {{-- Icon BookOpen --}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-indigo-600"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                        </div>
                        <div class="pt-0">
                            <div class="text-2xl font-bold">{{ $stats['enrolled'] }}</div>
                            <p class="text-xs text-gray-500 mt-1">Total kursus aktif</p>
                        </div>
                    </div>
            
                    {{-- Card 2: Kursus Selesai --}}
                    <div class="rounded-xl border border-gray-200 bg-white text-gray-950 shadow-sm hover:shadow-md transition-all p-6">
                        <div class="flex flex-row items-center justify-between space-y-0 pb-2">
                            <h3 class="tracking-tight text-sm font-medium text-gray-500">Kursus Selesai</h3>
                            {{-- Icon Award --}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-purple-600"><circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/></svg>
                        </div>
                        <div class="pt-0">
                            <div class="text-2xl font-bold">{{ $stats['completed'] }}</div>
                            <p class="text-xs text-gray-500 mt-1">Kursus yang telah diselesaikan</p>
                        </div>
                    </div>
            
                    {{-- Card 3: Sertifikat --}}
                    <div class="rounded-xl border border-gray-200 bg-white text-gray-950 shadow-sm hover:shadow-md transition-all p-6">
                        <div class="flex flex-row items-center justify-between space-y-0 pb-2">
                            <h3 class="tracking-tight text-sm font-medium text-gray-500">Sertifikat</h3>
                            {{-- Icon TrendingUp --}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-500"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                        </div>
                        <div class="pt-0">
                            <div class="text-2xl font-bold">{{ $stats['certificates'] }}</div>
                            <p class="text-xs text-gray-500 mt-1">Sertifikat diperoleh</p>
                        </div>
                    </div>
            
                    {{-- Card 4: Jam Belajar --}}
                    <div class="rounded-xl border border-gray-200 bg-white text-gray-950 shadow-sm hover:shadow-md transition-all p-6">
                        <div class="flex flex-row items-center justify-between space-y-0 pb-2">
                            <h3 class="tracking-tight text-sm font-medium text-gray-500">Jam Belajar</h3>
                            {{-- Icon Clock --}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-500"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        </div>
                        <div class="pt-0">
                            <div class="text-2xl font-bold">{{ $stats['totalHours'] }}</div>
                            <p class="text-xs text-gray-500 mt-1">Total jam pembelajaran</p>
                        </div>
                    </div>
                </div>
            
                {{-- 4. RECENT ACTIVITY (Aktivitas Terbaru) --}}
                <div class="rounded-xl border border-gray-200 bg-white text-gray-950 shadow-sm">
                    <div class="p-6 flex flex-col space-y-1.5 border-b border-gray-100">
                        <h3 class="font-semibold leading-none tracking-tight">Aktivitas Terbaru</h3>
                    </div>
                    <div class="p-6">
                        @if($user->enrollments->count() > 0)
                            <div class="space-y-8">
                                {{-- Loop 3 pendaftaran terakhir --}}
                                @foreach($user->enrollments->sortByDesc('updated_at')->take(3) as $enrollment)
                                <div class="flex items-center">
                                    <span class="relative flex h-2 w-2 mr-4">
                                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                      <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                                    </span>
                                    <div class="space-y-1">
                                        <p class="text-sm font-medium leading-none">
                                            {{ $enrollment->completed_at ? 'Menyelesaikan kursus' : 'Sedang mempelajari' }} 
                                            <span class="font-bold text-gray-800">{{ $enrollment->course->title }}</span>
                                        </p>
                                        <p class="text-xs text-muted-foreground text-gray-500">
                                            {{ $enrollment->updated_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    <div class="ml-auto font-medium text-sm {{ $enrollment->completed_at ? 'text-green-600' : 'text-gray-600' }}">
                                        {{ $enrollment->completed_at ? 'Selesai' : $enrollment->progress_percent . '%' }}
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-6">
                                <p class="text-sm text-muted-foreground text-gray-500 mb-4">
                                    Belum ada aktivitas. Mulai belajar dengan memilih kursus!
                                </p>
                                {{-- Button CTA jika kosong --}}
                                <button class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-gray-900 text-white hover:bg-gray-900/90 h-9 px-4 py-2">
                                    Lihat Katalog
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            
            </div>
            
        </div>
    </div>
</x-app-layout>