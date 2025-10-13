
<div>
<!-- success Alert -->
@if (session('message'))
<div x-data="{ alertIsVisible: true }" x-show="alertIsVisible" class="relative w-full overflow-hidden rounded-radius border border-success bg-surface text-on-surface dark:bg-surface-dark dark:text-on-surface-dark" role="alert" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
    <div class="flex w-full items-center gap-2 bg-success/10 p-4">
        <div class="bg-success/15 text-success rounded-full p-1" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-6" aria-hidden="true">
                <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-2">
            <h3 class="text-sm font-semibold text-success">Mensaje de información </h3>
            <p class="text-xs font-medium sm:text-sm">{{ session('message') }}</p>
        </div>
        <button type="button" @click="alertIsVisible = false" class="ml-auto" aria-label="dismiss alert">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="2.5" class="w-4 h-4 shrink-0">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
</div>
<br>
@endif
<!-- Botón crear -->
<button wire:click="create" class="px-4 py-2 bg-emerald-600 text-white rounded">Nuevo Usuario</button>

<!-- Tabla -->
<div class ="w-full p-6 bg-surface-alt dark:bg-surface-dark-alt rounded-lg shadow-md">
    <table class="w-full mt-4 border" style="border-color:rgba(53, 118, 216, 1);">
        <thead class="border-b border-outline bg-surface-alt text-sm text-on-surface-strong dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark-strong" style="border-color:rgba(53, 118, 216, 1);">
            <tr style="background-color: rgba(39, 68, 112, 1); color: white;">
                <th class="px-4 py-2">No.</th>
                <th class="px-4 py-2">Nombre</th>
                <th class="px-4 py-2">Usuario</th>
                <th class="px-4 py-2">Correo</th>
                <th class="px-4 py-2">Rol</th>
                <th class="px-4 py-2">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr class="even:bg-primary/5 dark:even:bg-primary-dark/10" style="border-color:rgba(53, 118, 216, 1);">
                    <td class="px-4 py-2">{{ $loop->iteration }}</td>
                    <td class="px-4 py-2">{{ $user->name }}</td>
                    <td class="px-4 py-2">{{ $user->username }}</td>
                    <td class="px-4 py-2">{{ $user->email }}</td>
                    <td class="px-4 py-2">
                        @php
                            $roleName = $user->roles->pluck('name')->first();
                        @endphp

                        {{ $roleName === 'admin' ? 'Administrador' : ucfirst($roleName ?? 'Sin rol') }}
                    </td>                <td class="px-4 py-2 flex gap-2">
                        <button 
                            wire:click="edit({{ $user->id }})"
                            class="text-blue-500 hover:text-blue-700 cursor-pointer transition-colors duration-200"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" 
                                viewBox="0 0 24 24" 
                                fill="currentColor" 
                                class="size-6">
                                    <title>Editar usuario</title>
                                    <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z" />
                                    <path d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z" />
                            </svg>
                        </button>
                        <button wire:confirm="¿Estás seguro de eliminar al usuario {{ $user->name }}?" 
                            wire:click="delete({{ $user->id }})" 
                            class="text-red-600 hover:text-red-800 cursor-pointer transition-colors duration-200"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" 
                                viewBox="0 0 24 24" 
                                fill="currentColor" 
                                class="size-6">
                                <title>Eliminar</title>
                                <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{ $users->links() }}

<!-- Modal -->
@if($isOpen)

    <div class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center">
        <div class="bg-white p-6 rounded w-1/3"style="background-image: url('/inicio/banner.png');" >
            <h2 class="text-lg font-bold mb-4 text-white">
                {{ $userId ? 'Editar Usuario' : 'Nuevo Usuario' }}
            </h2>

            <div class="mb-3">
                <label class="text-white">Nombre</label>
                <input type="text" wire:model="name" class="w-full border rounded px-2 py-1 bg-white">
                @error('name') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="text-white">Nombre de usuario</label>
                <input type="text" wire:model="username" class="w-full border rounded px-2 py-1 bg-white">
                @error('username') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="text-white">Correo</label>
                <input type="email" wire:model="email" class="w-full border rounded px-2 py-1 bg-white">
                @error('email') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="text-white">Contraseña</label>
                <input type="password" wire:model="password" class="w-full border rounded px-2 py-1 bg-white">
                @error('password') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="text-white">Confirmar Contraseña</label>
                <input type="password" wire:model="password_confirmation" class="w-full border rounded px-2 py-1 bg-white">
            </div>

            <div class="mb-3">
                <label class="text-white">Rol</label>
                <select wire:model="role" class="w-full border rounded px-2 py-1 bg-white">
                    <option value="">Seleccionar rol</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                    @endforeach
                </select>
                @error('role') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>

            <div class="flex justify-end gap-2">
                <button wire:click="closeModal" class="px-3 py-1 bg-gray-300 rounded">Cancelar</button>
                <button wire:click="store" class="px-3 py-1 bg-emerald-600 text-white rounded">
                    {{ $userId ? 'Actualizar' : 'Guardar' }}
                </button>
            </div>
        </div>
    </div>
@endif
</div>
