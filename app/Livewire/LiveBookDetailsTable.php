<?php

namespace App\Livewire;


use Livewire\{Component, WithPagination, WithoutUrlPagination};
use App\Models\Libro;
use App\Models\Ejemplar;
use Illuminate\Support\Facades\Log;

class LiveBookDetailsTable extends Component
{
    use WithPagination;

    public $search = ''; // Variable para almacenar el texto de búsqueda
    public $perPage = 5; // Número de usuarios por página
    public $sortField = null; // Campo por el que se ordena
    public $sortDirection = null; // Dirección de ordenamiento
    public $icon = '-circle'; // Icono de ordenamiento
    public $showModal = 'hidden';
    public $ejemplares = []; // Colección de ejemplares del libro
    public $id_libro; // ID del libro para el que se mostrarán los ejemplares



    /* Variables para la gestion del usuario a editar o eliminar*/
    public $selectedBookId;
    public $selectedBook;
    protected $listeners = [
        'refreshBookDetailsTable' => '$refresh',
        'successEditingBook' => 'showSuccessAlert',
        'showDeleteMessage' => 'showDeleteMessage',
        'clearDetailsFilters' => 'clearDetailsFilters', // Escucha el evento para limpiar los filtros


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
        /* HAY QUE AGREGAR MAS ATRIBUTOS DE BUSQUEDA ********************************************************************************** */
        $bookCopies = Ejemplar::where('id_libro', $this->id_libro) // Filtra por el id del libro
            ->where(function ($query) {
                $query->where('ubicacion_estante', 'LIKE', "%{$this->search}%")
                    ->orWhere('status', 'LIKE', "%{$this->search}%")
                    /* ->orWhere('fecha_prestamo', 'LIKE', "%{$this->search}%")
                    ->orWhere('fecha_devolucion_esperada', 'LIKE', "%{$this->search}%") */;
            });



        /* Se verifica si el campo de ordenamiento y la dirección no son nulos y se ordena los datos */
        if ($this->sortField && $this->sortDirection) {
            $bookCopies = $bookCopies->orderBy($this->sortField, $this->sortDirection);
        } else {
            $this->sortField = null; // Si no hay campo de ordenamiento, no se ordena
            $this->sortDirection = null; // Si no hay dirección de ordenamiento, no se ordena
        }

        /* Se paginan los datos */
        $bookCopies = $bookCopies->paginate($this->perPage);

        /* Retorna la vista con los datos paginados y ordenados */
        return view('livewire.live-book-details-table', [
            'bookCopies' => $bookCopies,

            // Obtiene todos los autores
        ]);
    }


    public function mount()
    {

        $this->icon = $this->iconDirection($this->sortDirection); // Cambia el icono según la dirección de ordenamiento
    }


    public function clearDetailsFilters()
    {
        $this->reset(['search', 'perPage', 'sortField', 'sortDirection']);
        $this->render(); // Vuelve a renderizar la vista para aplicar los cambios
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
