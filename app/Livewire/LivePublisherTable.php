<?php

namespace App\Livewire;


use Livewire\{Component, WithPagination, WithoutUrlPagination};
use App\Models\Editorial;
use Illuminate\Support\Facades\Log;

class LivePublisherTable extends Component
{
    use WithPagination;

    public $search = ''; // Variable para almacenar el texto de búsqueda
    public $perPage = 5; // Número de usuarios por página
    public $sortField = null; // Campo por el que se ordena
    public $sortDirection = null; // Dirección de ordenamiento
    public $icon = '-circle'; // Icono de ordenamiento
    public $showModal = 'hidden';


    /* Variables para la gestion del usuario a editar o eliminar*/
    public $selectedPublisherId;
    public $selectedPublisher;
    protected $listeners = [
        'refreshPublisherTable' => '$refresh',
        'successEditingPublisher' => 'showSuccessAlert',
        'showDeleteMessage' => 'showDeleteMessage',
        'refreshPublisherTable' => '$refresh',

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
        $publishers = Editorial::where('nombre_editorial', 'LIKE', "%{$this->search}%");



        /* Se verifica si el campo de ordenamiento y la dirección no son nulos y se ordena los datos */
        if ($this->sortField && $this->sortDirection) {
            $publishers = $publishers->orderBy($this->sortField, $this->sortDirection);
        } else {
            $this->sortField = null; // Si no hay campo de ordenamiento, no se ordena
            $this->sortDirection = null; // Si no hay dirección de ordenamiento, no se ordena
        }

        /* Se paginan los datos */
        $publishers = $publishers->paginate($this->perPage);

        /* Retorna la vista con los datos paginados y ordenados */
        return view('livewire.live-publisher-table', [
            'publishers' => $publishers,

            // Obtiene todos los autores
        ]);
    }


    public function mount()
    {

        $this->icon = $this->iconDirection($this->sortDirection); // Cambia el icono según la dirección de ordenamiento
    }



    public function openPublisherModal($mode, $id)
    {
        $this->dispatch('openPublisherModal', $mode, $id); // Dispara el evento para abrir el modal de creación de categoría
    }

    public function showDeleteConfirmation($rut, $name)
    {
        $this->dispatch('delete-attempt', model: "Publisher", id: $rut, title: "Eliminando editorial", text: "¿Está seguro de eliminar la editorial:\n{$name}?\nEsta acción solo la puede deshacer un administrador.");
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
