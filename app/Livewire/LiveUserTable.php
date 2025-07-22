<?php

namespace App\Livewire;

use Livewire\{Component, WithPagination, WithoutUrlPagination};
use App\Models\User;
use Illuminate\Support\Facades\Log;



class LiveUserTable extends Component
{
    use WithPagination;

    public $search = ''; // Variable para almacenar el texto de búsqueda
    public $perPage = 5; // Número de usuarios por página
    public $sortField = null; // Campo por el que se ordena
    public $sortDirection = null; // Dirección de ordenamiento
    public $icon = '-circle'; // Icono de ordenamiento
    public $showModal = 'hidden';

    /* Variables para la gestion del usuario a editar o eliminar*/
    public $selectedUserId;
    public $selectedUser;
    protected $listeners = [
        'refreshUserTable' => '$refresh',
        'successEditingUser' => 'showSuccessAlert',
        'showDeleteMessage' => 'showDeleteMessage',
        'destroyRegister' => 'destroyRegister',

    ]; // Escucha el evento de actualización del usuario

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 5],
        'sortField' => ['except' => null],
        'sortDirection' => ['except' => null],
    ];


    public function render()
    {
        /* Se genera la query para traer los datos según el texto de búsqueda */
        $users = User::where('name', 'LIKE', "%{$this->search}%")
            ->orWhere('rut_usuario', 'LIKE', "%{$this->search}%")
            ->orWhere('lastname', 'LIKE', "%{$this->search}%")
            ->orWhere('lastname2', 'LIKE', "%{$this->search}%")
            ->orWhere('email', 'LIKE', "%{$this->search}%")
            ->orWhere('phone', 'LIKE', "%{$this->search}%")
            ->orWhere('status', 'LIKE', "%{$this->search}%")
            ->orWhere('tipo_usuario', 'LIKE', "%{$this->search}%");

        /* Se verifica si el campo de ordenamiento y la dirección no son nulos y se ordena los datos */
        if ($this->sortField && $this->sortDirection) {
            $users = $users->orderBy($this->sortField, $this->sortDirection);
        } else {
            $this->sortField = null; // Si no hay campo de ordenamiento, no se ordena
            $this->sortDirection = null; // Si no hay dirección de ordenamiento, no se ordena
        }

        /* Se paginan los datos */
        $users = $users->paginate($this->perPage);

        /* Retorna la vista con los datos paginados y ordenados */
        return view('livewire.live-user-table', [
            'users' => $users,

            // Obtiene todos los usuarios
        ]);
    }

    public function mount()
    {

        $this->icon = $this->iconDirection($this->sortDirection); // Cambia el icono según la dirección de ordenamiento
    }

    public function dispatchUserData($id)

    {
        $this->selectedUserId = $id; // Almacena el ID del usuario seleccionado
        $this->selectedUser = User::find($id); // Busca el usuario por ID
        $this->dispatch('openEditUserModal', $this->selectedUser); // Dispara el evento para abrir el modal        
    }

    public function openUserModal($mode, $id)
    {
        $this->dispatch('openUserModal', $mode, $id); // Dispara el evento para abrir el modal de creación de usuario
    }
    public function openDeletedUserModal()
    {
        $this->dispatch('openDeletedUserModal'); // Dispara el evento para abrir el modal de creación de usuario
    }

    public function showDeleteConfirmation($id, $name, $lastname, $lastname2)
    {
        $this->dispatch('delete-attempt', model: "User", id: $id, title: "Eliminando usuario", text: "¿Está seguro de eliminar al usuario:\n{$name} {$lastname} {$lastname2}?\nEsta acción solo la puede deshacer un administrador.");
    }

    public function destroyRegister($id)
    {
        $this->selectedUserId = $id; // Almacena el ID del usuario seleccionado
        $this->selectedUser = User::find($id); // Busca el usuario por ID
        $this->dispatch('openUserModal', $this->selectedUser); // Dispara el evento para abrir el modal de eliminación
    }

    public function clear()
    {
        $this->reset();
    }

    /* METODOS DE FILTRADO Y BUSQUEDA */
    public function updatingSearch()
    {
        $this->resetPage(); // Resetea la página al cambiar el texto de búsqueda
    }

    public function sortBy($sortField)
    {

        /* Si el campo es distinto, se resetea la dirección de ordenamiento */
        if ($sortField != $this->sortField) {
            $this->sortDirection = null;
        }
        switch ($this->sortDirection) {
            case null:
                $this->sortDirection = 'asc';

                break;
            case 'asc':
                $this->sortDirection = 'desc';

                break;
            case 'desc':
                $this->sortDirection = null;

                break;
        }
        $this->sortField = $sortField;
        $this->icon = $this->iconDirection($this->sortDirection); // Cambia el icono según la dirección de ordenamiento
    }

    public function iconDirection($sortDirection): string
    {
        if (!$sortDirection) {
            return '-circle';
        }
        return $sortDirection === 'asc' ? '-arrow-circle-up' : '-arrow-circle-down';
    }
}
