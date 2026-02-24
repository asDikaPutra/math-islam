{{-- File: resources/views/auth/login.blade.php --}}

<x-guest-layout>

    {{-- HEADER (Logo, Judul) --}}
   {{-- HEADER BARU SESUAI GAMBAR --}}
    <div class="space-y-4 p-6 pb-0">
        
        {{-- BLOK UTAMA: [LOGO] | [GARIS] | [TEKS] --}}
        <div class="flex justify-center items-center space-x-4">
            
            {{-- 1. LOGO UIN --}}
            {{-- Saya asumsikan config('lms.logo.light') berisi logo UIN --}}
            @if (config('lms.logo.light'))
                <img src="{{ config('lms.logo.light') }}"
                     alt="Logo"
                     class="h-20 w-auto object-contain"> {{-- Ukuran disesuaikan (semula h-28) --}}
            @else
                {{-- Fallback icon Anda --}}
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center shadow-lg
                            {{ config('lms.colors.primary_bg') }}
                            {{ config('lms.colors.primary_shadow') }}">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M12 14l9-5-9-5-9 5 9 5zm0 0v7m-4 0h8" />
                    </svg>
                </div>
            @endif

            {{-- 2. GARIS PEMISAH --}}
            <div class="h-20 border-l border-gray-300"></div> {{-- Samakan tinggi dengan logo h-20 --}}

            {{-- 3. BLOK TEKS (e-Knows) --}}
            <div class="text-left">
                {{-- TITLE (e-Knows) --}}
                <h1 class="text-3xl font-bold bg-gradient-to-r bg-clip-text text-transparent
                           {{ config('lms.colors.gradient_from') }}
                           {{ config('lms.colors.gradient_to') }}">
                    {{ config('lms.title') }}
                </h1>
                {{-- SUBTITLE (e-Learning...) --}}
                <p class="text-sm"> {{-- Warna Oranye sesuai gambar --}}
                    {{ config('lms.subtitle') }}
                </p>
            </div>
        </div>

        {{-- NAMA UNIVERSITAS (Tambahan baru sesuai gambar) --}}
        <!-- <div class="text-center pt-2"> {{-- Ini akan rata tengah --}}
            <p class="text-xs text-gray-500 leading-tight">
                UNIVERSITAS ISLAM NEGERI
                <br>
                SUNAN GUNUNG DJATI BANDUNG
            </p>
        </div> -->
        
    </div>

    {{-- TABS --}}
    <div class="p-6">
        @if (config('lms.auth.allow_registration'))
        <div class="grid grid-cols-{{ config('lms.auth.allow_registration') ? 2 : 1 }}
                    mb-6 border rounded-xl overflow-hidden">

            {{-- Tab Login (Aktif by default) --}}
            <button id="tabLogin"
                    onclick="switchTab('login')"
                    class="py-2 font-medium
                           {{ config('lms.colors.primary_text') }}
                           {{ config('lms.colors.primary_bg_light') }}">
                Login
            </button>

            {{-- Tab Daftar (Inactive by default) --}}
            <button id="tabSignup"
                    onclick="switchTab('signup')"
                    class="py-2 font-medium
                           {{ config('lms.colors.neutral_text') }}
                           {{ config('lms.colors.neutral_bg_hover') }}">
                Daftar
            </button>

        </div>
        @endif

        {{-- FORM LOGIN --}}
        <div id="contentLogin">
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                {{-- Input NIM --}}
                <div class="space-y-2">
                    <label class="text-sm font-medium">
                        {{ str(config('lms.auth.login_with'))->upper() }}
                    </label>
                    <input id="nim"
                           type="text"
                           name="{{ config('lms.auth.login_with') }}"
                           required
                           class="w-full border-gray-300 rounded-lg shadow-sm 
                                  {{ config('lms.colors.primary_focus_border') }}
                                  {{ config('lms.colors.primary_focus_ring') }}
                                  @error(config('lms.auth.login_with')) {{ config('lms.colors.danger_border') }} @enderror"
                           placeholder="Masukkan {{ config('lms.auth.login_with') }}"
                           value="{{ old(config('lms.auth.login_with')) }}">
                    @error(config('lms.auth.login_with'))
                    <p class="text-sm {{ config('lms.colors.danger_text') }}">{{ $message }}</p>
                    @enderror
                </div>
                {{-- Input Password --}}
                <div class="space-y-2">
                    <label class="text-sm font-medium">Password</label>
                    <input id="password" type="password" name="password" required
                           class="w-full border-gray-300 rounded-lg shadow-sm 
                                  {{ config('lms.colors.primary_focus_border') }}
                                  {{ config('lms.colors.primary_focus_ring') }}
                                  @error('password') {{ config('lms.colors.danger_border') }} @enderror"
                           placeholder="Masukkan password">
                    @error('password')
                    <p class="text-sm {{ config('lms.colors.danger_text') }}">{{ $message }}</p>
                    @enderror
                </div>
                {{-- Tombol Submit --}}
                <button type="submit"
                        class="w-full py-2 rounded-lg text-white font-semibold shadow 
                               {{ config('lms.colors.primary_bg') }}
                               {{ config('lms.colors.primary_bg_hover') }}">
                    Login
                </button>
            </form>
        </div>

        {{-- SIGNUP FORM --}}
        @if (config('lms.auth.allow_registration'))
        <div id="contentSignup" class="hidden text-center text-gray-500">
            <p>Pendaftaran mahasiswa akan dibuka oleh administrator.</p>
        </div>
        @endif
    </div>

    {{-- TAB SWITCH SCRIPT (DIPERBARUI) --}}
    @if (config('lms.auth.allow_registration'))
    <script>
        window.LMS_THEME_CONFIG = {
            colors: {
                activeBg: @json(config('lms.colors.primary_bg_light')),
                activeText: @json(config('lms.colors.primary_text')),
                inactiveText: @json(config('lms.colors.neutral_text')),
                inactiveHover: @json(config('lms.colors.neutral_bg_hover'))
            }
        };
    </script>
    <script>
        // Ambil SEMUA nama kelas dari config Blade
        const activeBgClass = window.LMS_THEME_CONFIG.colors.activeBg;
        const activeTextClass = window.LMS_THEME_CONFIG.colors.activeText;

        const inactiveTextClass = window.LMS_THEME_CONFIG.colors.inactiveText;
        const inactiveHoverClass = window.LMS_THEME_CONFIG.colors.inactiveHover;

        function switchTab(tab) {
            const loginTab = document.getElementById("tabLogin");
            const signupTab = document.getElementById("tabSignup");
            const loginContent = document.getElementById("contentLogin");
            const signupContent = document.getElementById("contentSignup");

            if (tab === "login") {
                loginTab.classList.add(activeBgClass, activeTextClass);
                loginTab.classList.remove(inactiveTextClass, inactiveHoverClass);
                signupTab.classList.remove(activeBgClass, activeTextClass);
                signupTab.classList.add(inactiveTextClass, inactiveHoverClass);
                loginContent.classList.remove("hidden");
                signupContent.classList.add("hidden");
            } else {
                signupTab.classList.add(activeBgClass, activeTextClass);
                signupTab.classList.remove(inactiveTextClass, inactiveHoverClass);
                loginTab.classList.remove(activeBgClass, activeTextClass);
                loginTab.classList.add(inactiveTextClass, inactiveHoverClass);
                signupContent.classList.remove("hidden");
                loginContent.classList.add("hidden");
            }
        }
        
        // Tambahan: Pastikan tab login aktif jika ada error validasi
        @if ($errors->any())
            switchTab('login');
        @endif
    </script>
    @endif

</x-guest-layout>