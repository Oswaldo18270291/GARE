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
        @if ($errors->any())
            <div x-data="{ alertIsVisible: true }" x-show="alertIsVisible" class="relative w-full overflow-hidden rounded-radius border border-danger bg-surface text-on-surface dark:bg-surface-dark dark:text-on-surface-dark" role="alert" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
                <div class="flex w-full items-center gap-2 bg-danger/10 p-4">
                    <div class="bg-danger/15 text-danger rounded-full p-1" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-6" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16ZM8.28 7.22a.75.75 0 0 0-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 1 0 1.06 1.06L10 11.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L11.06 10l1.72-1.72a.75.75 0 0 0-1.06-1.06L10 8.94 8.28 7.22Z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-2">
                        <h3 class="text-sm font-semibold text-danger">Los datos de acceso no son válidos.</h3>
                        <p class="text-xs font-medium sm:text-sm">Parece que tu usuario o contraseña no coinciden. Inténtalo nuevamente.</p>
                    </div>
                </div>
            </div>
        @endif
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
