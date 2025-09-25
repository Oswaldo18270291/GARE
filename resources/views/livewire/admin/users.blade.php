
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
<table class="w-full mt-4 border">
    <thead>
        <tr class="bg-gray-100">
            <th class="px-4 py-2">No.</th>
            <th class="px-4 py-2">Nombre</th>
            <th class="px-4 py-2">Username</th>
            <th class="px-4 py-2">Email</th>
            <th class="px-4 py-2">Rol</th>
            <th class="px-4 py-2">Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
            <tr class="border-b">
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
                    <button wire:click="edit({{ $user->id }})" class="px-3 py-1 bg-blue-500 text-white rounded">Editar</button>
                    <button wire:confirm="¿Estás seguro de eliminar al usuario {{ $user->name }}?" wire:click="delete({{ $user->id }})" class="px-3 py-1 bg-red-500 text-white rounded">Eliminar</button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{ $users->links() }}

<!-- Modal -->
@if($isOpen)

    <div class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center">
        <div class="bg-white p-6 rounded w-1/3">
            <h2 class="text-lg font-bold mb-4">
                {{ $userId ? 'Editar Usuario' : 'Nuevo Usuario' }}
            </h2>

            <div class="mb-3">
                <label>Nombre</label>
                <input type="text" wire:model="name" class="w-full border rounded px-2 py-1">
                @error('name') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label>Username</label>
                <input type="text" wire:model="username" class="w-full border rounded px-2 py-1">
                @error('username') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" wire:model="email" class="w-full border rounded px-2 py-1">
                @error('email') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label>Contraseña</label>
                <input type="password" wire:model="password" class="w-full border rounded px-2 py-1">
                @error('password') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label>Confirmar Contraseña</label>
                <input type="password" wire:model="password_confirmation" class="w-full border rounded px-2 py-1">
            </div>

            <div class="mb-3">
                <label>Rol</label>
                <select wire:model="role" class="w-full border rounded px-2 py-1">
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
