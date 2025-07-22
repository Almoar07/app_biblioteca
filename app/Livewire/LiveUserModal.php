<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;

use App\Http\Controllers\UserController;
use Exception;
use Livewire\Attributes\Validate;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use App\Enums\UserType;
use App\Enums\UserStatus;



class LiveUserModal extends Component
{
    protected $listeners = [
        'openEditUserModal' => 'loadUser',
        'openUserModal' => 'handleOpenUserModal',
        'validationAlert' => 'showValidationAlert',
        'deleteUser' => 'handleDeleteUserEvent',



    ];


    public $showModal = false; // Variable para controlar la visibilidad del modal

    public string $mode = ''; // create | edit para verificar el contenido del modal
    public $disabled = false; // Variable para deshabilitar los campos del modal
    /* Datos del usuario */
    public $user;
    public $userId;
    public $userRut; // RUT del usuario,
    public $userName;
    public $userLastname;
    public $userLastname2;
    public $userEmail;
    public $userPhone;
    public $userStatus;
    public $userTipoUsuario;
    public $userBirthday;
    public $liveUserTable;

    public function handleDeleteUserEvent($id)
    {
        $this->deleteUser($id);
    }

    public function handleOpenUserModal($mode, $id)
    {
        $this->reset(); // Reinicia todas las variables del componente

        switch ($mode) {
            case 'create':
                $this->user = null; // Reinicia los datos del usuario
                break;
            case 'edit':
                $this->user = User::findOrFail($id); // Carga el usuario por ID
                $this->loadUser($this->user); // Carga los datos del usuario seleccionado
                break;
            default:
                break;
        }
        $this->mode = $mode;
        $this->openModal();
    }
    public function render()
    {
        return view('livewire.live-user-modal');
    }



    public function loadUser($userData)
    {
        $this->userId = $userData['id']; // Asigna el RUT del usuario como ID
        $this->userRut = $userData['rut_usuario']; // Asigna el RUT del usuario
        $this->userName = $userData['name'];
        $this->userLastname = $userData['lastname'];
        $this->userLastname2 = $userData['lastname2'];
        $this->userEmail = $userData['email'];
        $this->userPhone = $userData['phone'];
        $this->userStatus = $userData['status'];
        $this->userTipoUsuario = $userData['tipo_usuario'];
        $this->userBirthday = $userData['birthday'];
    }

    public function createUser()
    {

        try {
            $this->validate([
                'userName' => 'required|string|max:255',
                'userLastname' => 'required|string|max:255',
                'userLastname2' => 'nullable|string|max:255',
                'userEmail' => 'required|email|max:255|unique:users,email',
                'userPhone' => 'nullable|string|max:15',
                'userTipoUsuario' => 'required|string|in:admin,bibliotecario,invitado',
                'userStatus' => 'required|string|in:activo,inactivo,bloqueado',
                'userBirthday' => 'required|date',
            ]);

            // Validar el RUT usando Laragear\Rut
            $rut = \Laragear\Rut\Rut::parse($this->userRut);
            if (!$rut->isValid()) {
                $this->showValidationAlert('El RUT ingresado no es válido.');
                return;
            }
            // Normalizar el RUT (formato con puntos y guion)
            $this->userRut = $rut->format();
        } catch (ValidationException $e) {
            $messages = implode(', ', $e->validator->errors()->all());
            $this->showValidationAlert($messages);
            return;
        } catch (Exception $e) {
            // Errores no relacionados con validación            
            $this->showValidationAlert("Error inesperado: " . $e->getMessage());
            return;
        }

        // Mapear las variables a las columnas de la tabla
        $data = [
            'rut_usuario' => $this->userRut, // Asigna el RUT del usuario
            'name' => $this->userName,
            'lastname' => $this->userLastname,
            'lastname2' => $this->userLastname2,
            'email' => $this->userEmail,
            'phone' => $this->userPhone,
            'tipo_usuario' => $this->userTipoUsuario,
            'status' => $this->userStatus,
            'birthday' => $this->userBirthday,
            'password' => Hash::make(Str::random(12)),

            'created_by' => Auth::user() ? Auth::user()->name . ' ' . Auth::user()->lastname : 'formulario de registro', // Registra quién creó al usuario

        ];


        try {
            $user = User::create($data);  // asigno el usuario creado sin cambiar nada más
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                $this->showValidationAlert('El RUT ingresado ya existe en el sistema.');
            } else {
                $this->showValidationAlert('Error al guardar el usuario: ' . $e->getMessage());
            }
            return;
        }

        // Enviar enlace sólo si $user fue creado correctamente
        if ($user) {
            Password::sendResetLink(['email' => $user->email]);
        }
        $this->dispatch("success-alert", title: "Usuario registrado", text: "Usuario registrado con éxito");

        $this->closeModal(); // Cierra el modal
        $this->dispatch('refreshUserTable');
        // Enviar enlace para configurar contraseña

    }

    public function updateUser()
    {
        try {
            $this->validate([
                'userRut' => 'required|string|max:12|unique:users,rut_usuario,' . $this->userId, // RUT del usuario
                'userName' => 'required|string|max:255',
                'userLastname' => 'required|string|max:255',
                'userLastname2' => 'nullable|string|max:255',
                'userEmail' => 'required|email|max:255|unique:users,email,' . $this->userId,
                'userPhone' => 'nullable|string|max:15',
                /* 'userTipoUsuario' => 'required|string|in:admin,bibliotecario,invitado',
                'userStatus' => 'required|string|in:activo,inactivo,bloqueado', */
                'userBirthday' => 'required|date',
            ]);

            // Validar el RUT usando Laragear\Rut
            $rut = \Laragear\Rut\Rut::parse($this->userRut);
            if (!$rut->isValid()) {
                $this->showValidationAlert('El RUT ingresado no es válido.');
                return;
            }
            // Normalizar el RUT (formato con puntos y guion)
            $this->userRut = $rut->format();
        } catch (ValidationException $e) {
            $messages = implode(', ', $e->validator->errors()->all());
            $this->showValidationAlert($messages);
            return;
        } catch (Exception $e) {
            // Errores no relacionados con validación            
            $this->showValidationAlert("Error inesperado: " . $e->getMessage());
            return;
        }


        // Mapear las variables a las columnas de la tabla
        $data = [
            'name' => $this->userName,
            'rut_usuario' => $this->userRut, // Asigna el RUT del usuario
            'lastname' => $this->userLastname,
            'lastname2' => $this->userLastname2,
            'email' => $this->userEmail,
            'phone' => $this->userPhone,
            'tipo_usuario' => is_string($this->userTipoUsuario)
                ? UserType::tryFrom($this->userTipoUsuario)
                : $this->userTipoUsuario,

            'status' => is_string($this->userStatus)
                ? UserStatus::tryFrom($this->userStatus)
                : $this->userStatus,
            'birthday' => $this->userBirthday,
        ];
        $user = User::findOrFail($this->userId);

        $user->update($data);


        $this->dispatch("success-alert", title: "Usuario actualizado", text: "Usuario actualizado con éxito");
        $this->closeModal(); // Cierra el modal
        $this->dispatch('refreshUserTable');
        $this->dispatch('profile-updated', name: $user->name);
    }

    public function deleteUser($id)
    {
        try {
            $user = User::findOrFail($id); // Busca el usuario por ID
            $loggedUser = Auth::user();
            $loggedUserName = $loggedUser ? $loggedUser->name : null;
            $loggedUserLastname = $loggedUser ? $loggedUser->lastname : null;
            $user->deleted_by = $loggedUserName . ' ' . $loggedUserLastname; // Registra quién eliminó al usuario
            /* El usuario pasa estar inactivo cuando se elimina */
            $user->status = 'inactivo'; // Cambia el estado del usuario a inactivo
            $user->save(); // Guarda el valor de deleted_by en la base de datos
            $user->delete(); // Elimina el usuario

            $this->dispatch('delete-success', model: "User", id: $id, title: "Usuario eliminado", text: "ID: {$user->id}\nNombre: {$user->name} {$user->lastname} {$user->lastname2}\n");
        } catch (Exception $e) {
            $this->showValidationAlert("Error al eliminar el usuario: " . $e->getMessage());
        }

        $this->closeModal(); // Cierra el modal
        $this->dispatch('refreshUserTable');
    }

    public function showSuccessAlert($typeAlert, $name, $lastname, $lastname2)
    {
        switch ($typeAlert) {
            case 'create':
                $this->dispatch('created-success', model: "user", title: "Usuario creado", text: "Usuario creado con éxito: $name $lastname $lastname2");
                break;
            case 'update':
                $this->dispatch('updated-success', model: "user", title: "Usuario actualizado", text: "Usuario actualizado con éxito: $name $lastname $lastname2");
                break;
            default:
                break;
        }
    }

    public function sendPasswordResetLink($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            $this->dispatch('error-alert', [
                'title' => 'Error',
                'text' => 'Usuario no encontrado.'
            ]);
            return;
        }

        $status = Password::sendResetLink(['email' => $user->email]);

        if ($status === Password::RESET_LINK_SENT) {
            $this->dispatch(
                "success-alert",
                title: "Correo de restablecimiento de contraseña enviado",
                text: "Se envío correo de restablecimiento de contraseña a {$user->email}."
            );
        } else {
            $this->dispatch(
                'error-alert',
                title: "No se envió el correo de restablecimiento de contraseña",
                text: $status
            );
        }
    }

    public function showValidationAlert($errors)
    {
        $this->dispatch('validation-alert', errors: $errors);
    }
    public function openModal()
    {
        $this->showModal = true; // Cambia la visibilidad del modal a visible

        switch ($this->mode) {
            case 'create':
                $this->userTipoUsuario = ''; // Establece el tipo de usuario por defecto


                break;
            case 'edit':
                $this->loadUser($this->user); // Carga los datos del usuario seleccionado
                break;
            default:
                break;
        }
    }

    public function closeModal()
    {
        $this->showModal = false; // Cambia la visibilidad del modal a oculto
        $this->reset(); // Reinicia las variables del modal
    }
}
