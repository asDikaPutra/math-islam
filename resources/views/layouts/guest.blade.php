<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('lms.title') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">

    {{--
      Container utama:
      - h-screen: Tepat 100% tinggi layar.
      - overflow-hidden: Mencegah seluruh halaman (body) agar tidak bisa di-scroll.
    --}}
    <div class="lg:grid lg:grid-cols-2 h-screen overflow-hidden">

        <div class="hidden lg:block relative h-screen">

            <img class="absolute inset-0 w-full h-full object-cover opacity-95"
                src="{{ config('lms.bg.login') }}"
                alt="Islamic Background">

            <div class="absolute inset-0 bg-blue-900 opacity-40"></div>

            <div class="absolute inset-0 flex items-center justify-center p-12">
                <div class="text-center">
                    <h1 class="text-4xl font-bold text-white drop-shadow-lg">
                        Selamat Datang
                    </h1>
                    <p class="text-xl text-blue-100 mt-2 drop-shadow-md">
                        {{ config('lms.subtitle', 'Learning Management System') }}
                    </p>
                </div>
            </div>
        </div>


        <div class="h-screen overflow-y-auto
                    bg-gradient-to-br from-indigo-50 via-white to-indigo-100 
                    lg:bg-gray-50">

            {{--
              Wrapper untuk centering:
              - Padding dikurangi menjadi 'p-6' (sebelumnya 'py-12 px-4')
              - 'min-h-full' agar 'items-center' berfungsi
            --}}
            <div class="flex justify-center items-center min-h-full p-6">

                {{-- Kartu Form Login Anda --}}
                <div class="w-full max-w-md bg-white border border-indigo-100 rounded-2xl shadow-xl">

                    {{-- KONTEN DARI 'login.blade.php' --}}
                    {{ $slot }}

                    {{-- FOOTER DINAMIS --}}
                    <div class="text-center text-sm text-gray-500 pb-6 px-4">
                        {{ config('lms.institution_name') }}
                        <br>
                        {{ config('lms.faculty_name') }}
                    </div>

                </div>

            </div>
        </div>

    </div>
</body>

</html>