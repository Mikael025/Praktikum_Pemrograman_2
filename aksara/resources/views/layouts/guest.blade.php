<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'LaravelPride') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#003830]">
        <div class="flex min-h-screen">
            <!-- Left side - Background area -->
            <div class="hidden lg:block lg:w-2/3 bg-[#003830]"></div>
            
            <!-- Right side - White form area -->
            <div class="w-full lg:w-1/3 p-8 space-y-6 bg-[#FFFFFF] rounded-l-3xl shadow-lg flex flex-col justify-center">
                <!--Logo Png File-->
                <div class="flex justify-center pt-26">
                    <a href="/">
                        <img src="{{ asset('images/logoAksara.png') }}" alt="Logo {{ config('app.name', 'Laravel') }}"  class="h-[250px] w-[300px] drop-shadow-lg">
                    </a>
                </div>
                <!-- Title -->
                <div class="text-center">
                    <h1 class="text-5xl font-bold text-text-light dark:text-text-dark pb-2">
                        Let's sign you in
                    </h1>
                    <p class="text-2xl text-muted-light dark:text-muted-dark">Welcome to AKSARA</p>
                </div>
                                
                <div class="w-full max-w-md mx-auto mt-6 px-6 py-4  overflow-hidden">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
