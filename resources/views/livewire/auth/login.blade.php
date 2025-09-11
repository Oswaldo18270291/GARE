<div class="flex flex-col gap-6">
    <x-auth-header 
        :title="__('GARE')" 
        :description="__('Ingresa tus datos de acceso')" 
        titleClass="font-serif bold text-3xl text-white mb-2"
        descClass="text-white"

    />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form method="POST" wire:submit="login" class="flex flex-col gap-6 login-form text-white">
        <!-- Email Address -->
<flux:input
    id="username-input"
    wire:model="username"
    :label="__('Usuario')"
    required
    autofocus
    placeholder="7777_ejemplo"
/>

<style>
    #username-input[data-flux-input] label {
        color: white !important;
    }
</style>


        <!-- Password -->
        <div class="relative">
            <flux:input
                wire:model="password"
                :label="__('Ingrese contraseña')"
                type="password"
                required
                autocomplete="current-password"
                :placeholder="__('Contraseña')"
                viewable
                class="[&>label]:text-white"
            />

            @if (Route::has('password.request'))
                <flux:link class="absolute end-0 top-0 text-sm !text-blue-950 font-semibold" :href="route('password.request')" wire:navigate>
                    {{ __('¿Olvidó la contraseña?') }}
                </flux:link>
            @endif
        </div>

        <!-- Remember Me -->
        <flux:checkbox 
            wire:model="remember"  
            :label="__('Recuérdame')"  
            class="[&>label]:text-white"
        />

        <div class="flex items-center justify-end">
            <flux:button variant="primary" type="submit" class="w-full bg-blue-950">{{ __('Ingresar') }}</flux:button>
        </div>
    </form>

    @if (Route::has('register'))
        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-white dark:text-zinc-400">
            <span>{{ __('¿No tienes una cuenta?') }}</span>
            <flux:link 
                :href="route('register')"
                class="!text-blue-950 font-semibold"  
                wire:navigate>{{ __('Crear cuenta') }}
            </flux:link>
        </div>
    @endif
</div>
