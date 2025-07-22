<?php

namespace App\Livewire;

use Livewire\{Component, WithPagination, WithoutUrlPagination};
use App\Models\Estudiante;
use Illuminate\Support\Facades\Log;



class LiveStudentTable extends Component
{
    use WithPagination;

    public $search = ''; // Variable para almacenar el texto de búsqueda
    public $perPage = 5; // Número de usuarios por página
    public $sortField = null; // Campo por el que se ordena
    public $sortDirection = null; // Dirección de ordenamiento
    public $icon = '-circle'; // Icono de ordenamiento
    public $showModal = 'hidden';

    /* Variables para la gestion del usuario a editar o eliminar*/
    public $selectedStudentId;
    public $selectedStudent;
    protected $listeners = [
        'studentUpdatedRefreshTable' => '$refresh',
        'successEditingStudent' => 'showSuccessAlert',
        'showDeleteMessage' => 'showDeleteMessage',
        'refreshStudentTable' => '$refresh',
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
        $students = Estudiante::with('comuna') // Esto carga el nombre de la comuna
            ->where(function ($query) {
                $query->where('nombres', 'LIKE', "%{$this->search}%")
                    ->orWhere('rut_estudiante', 'LIKE', "%{$this->search}%")
                    ->orWhere('apellido_paterno', 'LIKE', "%{$this->search}%")
                    ->orWhere('apellido_materno', 'LIKE', "%{$this->search}%")
                    ->orWhere('direccion', 'LIKE', "%{$this->search}%")
                    ->orWhere('curso', 'LIKE', "%{$this->search}%")
                    ->orWhere('estado', 'LIKE', "%{$this->search}%")
                    ->orWhere('email', 'LIKE', "%{$this->search}%")
                    ->orWhere('telefono', 'LIKE', "%{$this->search}%");
            })
            ->orWhereHas('comuna', function ($query) {
                $query->where('nombre_comuna', 'LIKE', "%{$this->search}%");
            });

        /* Se verifica si el campo de ordenamiento y la dirección no son nulos y se ordena los datos */
        if ($this->sortField && $this->sortDirection) {
            $students = $students->orderBy($this->sortField, $this->sortDirection);
        } else {
            $this->sortField = null; // Si no hay campo de ordenamiento, no se ordena
            $this->sortDirection = null; // Si no hay dirección de ordenamiento, no se ordena
        }

        /* Se paginan los datos */
        $students = $students->paginate($this->perPage);

        /* Retorna la vista con los datos paginados y ordenados */
        return view('livewire.live-student-table', [
            'students' => $students,

            // Obtiene todos los estudiantes
        ]);
    }

    public function mount()
    {

        $this->icon = $this->iconDirection($this->sortDirection); // Cambia el icono según la dirección de ordenamiento
    }

    public function dispatchStudentData($id)
    {
        $this->selectedStudentId = $id; // Almacena el ID del usuario seleccionado
        $this->selectedStudent = Estudiante::find($id); // Busca el usuario por ID
        $this->dispatch('openEditStudentModal', $this->selectedStudent); // Dispara el evento para abrir el modal        
    }

    public function showDeleteConfirmation($rut, $name, $lastname, $lastname2)
    {
        $this->dispatch('delete-attempt', model: "Student", id: $rut, title: "Eliminando usuario", text: "¿Está seguro de eliminar al estudiante:\n{$name} {$lastname} {$lastname2}?\nEsta acción solo la puede deshacer un administrador.");
    }

    public function openStudentModal($mode, $id)
    {
        $this->dispatch('openStudentModal', $mode, $id); // Dispara el evento para abrir el modal de estudiante
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
