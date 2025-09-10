<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white bg-cover bg-center antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900" style="background-image: url('{{ asset('fondo_login/fondo_login.png') }}');">
        <div class="bg-background/70 flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
            <div class="flex w-full max-w-sm flex-col gap-2 bg-emerald-700 dark:bg-neutral-900/80 rounded-xl shadow-lg p-6">
                <a href="{{ route('login') }}" class="flex flex-col items-center gap-2 font-medium" wire:navigate>
                    <span class="flex h-40 w-40 mb-1 items-center justify-center rounded-md">
                        <img src="{{ asset('gare.png') }}" alt="Descripción de la imagen">                    </span>
                    <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
                </a>
                <div class="flex flex-col gap-6">
                    {{ $slot }}
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
