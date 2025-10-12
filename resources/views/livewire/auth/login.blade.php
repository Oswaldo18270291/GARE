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

        <div class="flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
            <label for="textInputDefault" class="w-fit pl-0.5 text-sm text-white">Usuario</label>
            <input wire:model="username" id="username-input" type="text" required autofocus class="w-full rounded-radius border border-outline bg-surface-alt px-2 py-2 
            text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 
            dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark " name="name" placeholder="7777_ejemplo" 
            autocomplete="name"/>
        </div>
        <!-- Password -->
        <div class="relative">
        <div class="flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
            <label for="textInputDefault" class="w-fit pl-0.5 text-sm text-white">Ingrese contraseña</label>
            <input wire:model="password" id="username-input" type="password" required autofocus class="w-full rounded-radius border border-outline bg-surface-alt px-2 py-2 
            text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 
            dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark " required autocomplete="current-password" name="password" placeholder="Contraseña" 
            autocomplete="name"/>
        </div>
            {{-- Password 
                @if (Route::has('password.request'))
                    <flux:link class="absolute end-0 top-0 text-sm !text-blue-950 font-semibold" :href="route('password.request')" wire:navigate>
                        {{ __('¿Olvidó la contraseña?') }}
                    </flux:link>
                @endif
            --}}
        </div>

        {{-- Remember Me

        <label for="remember" class="flex items-center gap-2 text-sm text-white cursor-pointer">
            <flux:checkbox 
                wire:model="remember"  
                class="[&>label]:text-white"
                descClass="text-white"
                id="remember"
            />
            <span>Recordar contraseña</span>
        </label>
        --}}


        <div class="flex items-center justify-end">
            <flux:button variant="primary" type="submit" class="w-full bg-blue-950">{{ __('Ingresar') }}</flux:button>
        </div>
    </form>

    {{-- creación de cuenta
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
    --}}
</div>
