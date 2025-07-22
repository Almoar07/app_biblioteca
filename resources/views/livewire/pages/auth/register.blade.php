<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public string $name = '';
    public string $lastname = '';
    public string $lastname2 = '';
    public string $phone = '';
    public string $birthday = '';
    public string $tipo_usuario = 'bibliotecario';
    public string $status = 'inactivo';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'lastname' => ['required', 'string', 'max:255'],
                'lastname2' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'string', 'max:255'],
                'birthday' => ['required', 'date'],
                'tipo_usuario' => ['required', 'string', 'max:255'],
                'status' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
                'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            ],
            [
                'name.required' => 'El campo nombre es obligatorio.',
                'lastname.required' => 'El campo apellido paterno es obligatorio.',
                'lastname2.required' => 'El campo apellido materno es obligatorio.',
                'email.required' => 'El campo correo electrónico es obligatorio.',
                'email.unique' => 'El correo electrónico ya está registrado.',
                'birthday.required' => 'El campo fecha de nacimiento es obligatorio.',
                'phone.required' => 'El campo teléfono es obligatorio.',
                'tipo_usuario.required' => 'El campo tipo de usuario es obligatorio.',
                'status.required' => 'El campo estado es obligatorio.',
                'password.required' => 'El campo contraseña es obligatorio.',
                'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            ],
        );

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>


<div>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form wire:submit="register">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Nombre')" />
                <x-text-input wire:model="name" id="name" class="block mt-1 w-full" type="text" name="name"
                    required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Apellido Paterno -->
            <div>
                <x-input-label for="lastname" :value="__('Apellido paterno')" />
                <x-text-input wire:model="lastname" id="lastname" class="block mt-1 w-full" type="text"
                    name="lastname" required autofocus autocomplete="lastname" />
                <x-input-error :messages="$errors->get('lastname')" class="mt-2" />
            </div>
            <!-- Apellido Materno -->
            <div>
                <x-input-label for="lastname2" :value="__('Apellido materno')" />
                <x-text-input wire:model="lastname2" id="lastname2" class="block mt-1 w-full" type="text"
                    name="lastname2" required autofocus autocomplete="lastname2" />
                <x-input-error :messages="$errors->get('lastname2')" class="mt-2" />
            </div>
            <!-- Teléfono -->
            <div>
                <x-input-label for="phone" :value="__('Número de teléfono')" />
                <x-text-input wire:model="phone" id="phone" class="block mt-1 w-full" type="text" name="phone"
                    required autocomplete="username" />
                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            </div>
            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Correo electrónico')" />
                <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email"
                    required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
            <!-- Día de nacimiento -->
            <div>
                <x-input-label for="birthday" :value="__('Día de nacimiento')" />
                <x-text-input wire:model="birthday" id="birthday" class="block mt-1 w-full" type="date"
                    name="birthday" required autocomplete="username" />
                <x-input-error :messages="$errors->get('birthday')" class="mt-2" />
            </div>

            <!-- Password -->
            <div>
                <x-input-label for="password" :value="__('Contraseña')" />

                <x-text-input wire:model="password" id="password" class="block mt-1 w-full" type="password"
                    name="password" required autocomplete="new-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div>
                <x-input-label for="password_confirmation" :value="__('Confirme la contraseña')" />

                <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block mt-1 w-full"
                    type="password" name="password_confirmation" required autocomplete="new-password" />

                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                href="{{ route('login') }}" wire:navigate>
                {{ __('¿Ya tienes una cuenta? Inicia sesión') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Registrarse') }}
            </x-primary-button>
        </div>
    </form>
</div>
