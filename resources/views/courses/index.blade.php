<x-app-layout>
    {{-- Header Halaman --}}
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Katalog Kursus') }}
        </h2>
        <p class="text-gray-500 mt-2">
            Jelajahi dan daftar kursus yang tersedia
        </p>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- KONTEN UTAMA --}}
            <div>

                {{-- Pesan Sukses (Toast) --}}
                @if(session('success'))
                <div class="bg-green-50 text-green-700 p-4 rounded-md border border-green-200 mb-4">
                    {{ session('success') }}
                </div>
                @endif

                {{-- Pesan Error --}}
                @if(session('error'))
                <div class="bg-red-50 text-red-700 p-4 rounded-md border border-red-200 mb-4">
                    {{ session('error') }}
                </div>
                @endif

                {{-- Empty State (Jika tidak ada kursus) --}}
                @if($courses->isEmpty())
                <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
                    <div class="p-12 text-center">
                        {{-- Icon BookOpen --}}
                        <div class="mx-auto h-12 w-12 text-gray-400 mb-4 flex justify-center items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z" />
                                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z" />
                            </svg>
                        </div>
                        <p class="text-lg font-medium text-gray-900">Belum ada kursus tersedia</p>
                        <p class="text-sm text-gray-500 mt-2">
                            Kursus baru akan segera ditambahkan
                        </p>
                    </div>
                </div>
                @else
                {{-- Grid Kursus --}}
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($courses as $course)
                    @php
                    // Cek Level Warna
                    $levelColor = match(strtolower($course->level)) {
                    'beginner' => 'bg-green-100 text-green-800',
                    'intermediate' => 'bg-yellow-100 text-yellow-800',
                    'advanced' => 'bg-red-100 text-red-800',
                    default => 'bg-gray-100 text-gray-800',
                    };

                    // Cek Status Enrollment
                    $isEnrolled = in_array($course->id, $enrolledCourseIds);
                    @endphp

                    <div class="group flex flex-col overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm hover:shadow-lg transition-all">

                        {{-- Card Header: Gambar & Title --}}
                        <div class="p-6 pb-4">
                            {{-- Thumbnail --}}
                            <div class="aspect-video w-full rounded-lg mb-4 overflow-hidden relative bg-gradient-to-br from-indigo-50 to-purple-50 flex items-center justify-center">
                                @if($course->thumbnail)
                                <img src="{{ $course->thumbnail }}" alt="{{ $course->title }}" class="h-full w-full object-cover">
                                @else
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-indigo-300">
                                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z" />
                                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z" />
                                </svg>
                                @endif
                            </div>

                            <div class="flex items-start justify-between gap-2 mb-2">
                                <h3 class="font-semibold text-lg leading-tight line-clamp-2 text-gray-900">
                                    {{ $course->title }}
                                </h3>
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $levelColor }}">
                                    {{ ucfirst($course->level) }}
                                </span>
                            </div>

                            <p class="text-sm text-gray-500 line-clamp-2">
                                {{ $course->description }}
                            </p>
                        </div>

                        {{-- Card Content: Info Detail --}}
                        <div class="px-6 flex-grow">
                            <div class="space-y-3 text-sm">
                                {{-- Instruktur --}}
                                <div class="flex items-center gap-2 text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                                        <circle cx="12" cy="7" r="4" />
                                    </svg>
                                    {{-- Menggunakan null coalescing operator bertingkat untuk keamanan --}}
                                    <span>{{ $course->instructor?->profile?->full_name ?? $course->instructor?->name ?? 'Instruktur' }}</span>
                                </div>

                                {{-- Durasi --}}
                                <div class="flex items-center gap-2 text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10" />
                                        <polyline points="12 6 12 12 16 14" />
                                    </svg>
                                    <span>{{ $course->duration_hours ?? 0 }} Jam Pembelajaran</span>
                                </div>
                            </div>
                        </div>

                        {{-- Card Footer: Tombol Aksi --}}
                        <div class="p-6 pt-4 mt-auto">
                            @if($isEnrolled)
                            {{-- Link 'Lanjut Belajar' (Menggunakan tanda pagar # jika route learning belum siap) --}}
                            {{-- Jika route sudah siap, ganti '#' dengan route('learning.show', $course->slug) --}}
                            {{-- UPDATE BARIS INI --}}
                            <a href="{{ route('learning.show', $course->slug) }}"
                                class="inline-flex w-full items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Lanjut Belajar
                            </a>
                            @else
                            <form action="{{ route('courses.enroll', $course->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="inline-flex w-full items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                                    Daftar Kursus
                                </button>
                            </form>
                            @endif
                        </div>

                    </div>
                    @endforeach
                </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>