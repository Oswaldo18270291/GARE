<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;

class Users extends Component
{
    use WithPagination;

    public $name, $username, $email, $password, $password_confirmation, $role;
    public $userId;
    public $isOpen = false;


    public function render()
    {
        return view('livewire.admin.users', [
            'users' => User::with('roles')->paginate(10),
            'roles' => Role::all(),
        ]);
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isOpen = true;
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->role = $user->roles->pluck('name')->first() ?? '';
        $this->isOpen = true;
    }

    public function delete($id)
    {
        User::findOrFail($id)?->delete();
        session()->flash('message', 'Usuario eliminado correctamente.');
    }

    public function store()
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'max:50',
                Rule::unique('users', 'username')->ignore($this->userId, 'id'),
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->userId, 'id'),
            ],
            'password' => $this->userId
                ? ['nullable', 'string', 'confirmed', Rules\Password::defaults()]
                : ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'exists:roles,name'],
        ]);

        if ($this->userId) {
            // Actualizar usuario existente
            $user = User::findOrFail($this->userId);
            $user->update([
                'name' => $this->name,
                'username' => $this->username,
                'email' => $this->email,
                'password' => $this->password ? Hash::make($this->password) : $user->password,
            ]);
        } else {
            // Crear nuevo usuario
            $user = User::create([
                'name' => $this->name,
                'username' => $this->username,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);
        }

        // Asignar rol
        $user->syncRoles([$this->role]);

        session()->flash('message',
            $this->userId ? 'Usuario actualizado correctamente.' : 'Usuario creado correctamente.'
        );

        $this->closeModal();
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->username = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->role = '';
        $this->userId = null;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }
}
