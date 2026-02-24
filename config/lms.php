<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Informasi Dasar LMS
    |--------------------------------------------------------------------------
    */
    'title' => env('LMS_TITLE', 'LMS Fakultas'),
    'subtitle' => env('LMS_SUBTITLE', 'Learning Management System'),

    /*
    |--------------------------------------------------------------------------
    | Identitas Kampus / Fakultas
    |--------------------------------------------------------------------------
    */
    'institution_name' => env('LMS_INSTITUTION_NAME', 'Universitas Islam Negeri Sunan Gunung Djati Bandung'),
    'faculty_name' => env('LMS_FACULTY_NAME', 'Fakultas Sains dan Teknologi'),
    'department_name' => env('LMS_DEPARTMENT_NAME', null),

    /*
    |--------------------------------------------------------------------------
    | Tampilan Antarmuka (UI/Branding)
    |--------------------------------------------------------------------------
    */
    'logo' => [
        'light' => env('LMS_LOGO_LIGHT', '/images/logo-light.png'),
        'dark'  => env('LMS_LOGO_DARK', '/images/logo-dark.png'),
    ],

    'bg' => [
        'login' => env('LMS_BG_LOGIN', 'bg-login.jpg'),
    ],

    // 'theme' => [
    //     'primary_color'   => env('LMS_PRIMARY_COLOR', '#4F46E5'),      // Indigo-600
    //     'secondary_color' => env('LMS_SECONDARY_COLOR', '#9333EA'),    // Purple-600
    //     'accent_color'    => env('LMS_ACCENT_COLOR', '#10B981'),       // Green-500
    // ],

    'colors' => [
        // Warna Primer
        'neutral_text'       => env('LMS_COLOR_NEUTRAL_TEXT', 'text-gray-500'),
        'neutral_bg_hover'   => env('LMS_COLOR_NEUTRAL_BG_HOVER', 'hover:bg-gray-100'),
        'primary_bg'         => env('LMS_COLOR_PRIMARY_BG', 'bg-indigo-600'),
        'primary_bg_hover'   => env('LMS_COLOR_PRIMARY_BG_HOVER', 'hover:bg-indigo-700'),
        'primary_bg_light'   => env('LMS_COLOR_PRIMARY_BG_LIGHT', 'bg-indigo-100'),
        'primary_text'       => env('LMS_COLOR_PRIMARY_TEXT', 'text-indigo-600'),
        'primary_focus_ring' => env('LMS_COLOR_PRIMARY_FOCUS_RING', 'focus:ring-indigo-500'),
        'primary_focus_border' => env('LMS_COLOR_PRIMARY_FOCUS_BORDER', 'focus:border-indigo-500'),
        'gradient_from'  => env('LMS_COLOR_GRADIENT_FROM', 'from-blue-600'),
        'gradient_to'    => env('LMS_COLOR_GRADIENT_TO', 'to-green-600'),

        // Warna Bahaya (Error)
        'danger_text'        => env('LMS_COLOR_DANGER_TEXT', 'text-red-600'),
        'danger_border'      => env('LMS_COLOR_DANGER_BORDER', 'border-red-500'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Pengaturan Sistem Registrasi & Autentikasi
    |--------------------------------------------------------------------------
    */
    'auth' => [
        'allow_registration' => env('LMS_ALLOW_REGISTRATION', false),
        'login_with'         => env('LMS_LOGIN_WITH', 'nim'), // nim / email
        'force_password_change_on_first_login' => env('LMS_FORCE_PASSWORD_CHANGE', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Pengaturan Kursus, Modul, Sertifikat
    |--------------------------------------------------------------------------
    */
    'courses' => [
        'show_instructor_profile' => env('LMS_SHOW_INSTRUCTOR_PROFILE', true),
        'allow_course_self_enroll' => env('LMS_SELF_ENROLL', false),
    ],

    'certificates' => [
        'auto_generate' => env('LMS_CERT_AUTO_GENERATE', true),
        'signature_image' => env('LMS_CERT_SIGNATURE', '/images/ttd-dekan.png'),
        'template'        => env('LMS_CERT_TEMPLATE', 'default'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Pengaturan Keamanan (Security)
    |--------------------------------------------------------------------------
    */
    'security' => [
        'session_lifetime_minutes' => env('LMS_SESSION_MINUTES', 120),
        'max_login_attempts' => env('LMS_MAX_LOGIN_ATTEMPTS', 5),
        'lockout_minutes'    => env('LMS_LOCKOUT_MINUTES', 10),
    ],

];
