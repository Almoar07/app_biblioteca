<?php

namespace App\Livewire;


use Livewire\{Component, WithPagination, WithoutUrlPagination};
use App\Models\Libro;
use Illuminate\Support\Facades\Log;

class LiveBookTable extends Component
{
    use WithPagination;

    public $search = ''; // Variable para almacenar el texto de búsqueda
    public $perPage = 5; // Número de usuarios por página
    public $sortField = null; // Campo por el que se ordena
    public $sortDirection = null; // Dirección de ordenamiento
    public $icon = '-circle'; // Icono de ordenamiento
    public $showModal = 'hidden';


    /* Variables para la gestion del usuario a editar o eliminar*/
    public $selectedBookId;
    public $selectedBook;
    protected $listeners = [
        'refreshBookTable' => '$refresh',
        'successEditingBook' => 'showSuccessAlert',
        'showDeleteMessage' => 'showDeleteMessage',


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
        $books = Libro::with('autor')
            ->with('editorial')
            ->with('categoria')
            ->where('titulo', 'LIKE', "%{$this->search}%")
            ->orWhere('anio_publicacion', 'LIKE', "%{$this->search}%")
            ->orWhere('isbn', 'LIKE', "%{$this->search}%")
            ->orWhereHas('autor', function ($query) {
                $query->where('nombre', 'LIKE', "%{$this->search}%");
            })
            ->orWhereHas('autor', function ($query) {
                $query->where('apellido_paterno', 'LIKE', "%{$this->search}%");
            })
            ->orWhereHas('autor', function ($query) {
                $query->where('apellido_materno', 'LIKE', "%{$this->search}%");
            })
            ->orWhereHas('editorial', function ($query) {
                $query->where('nombre_editorial', 'LIKE', "%{$this->search}%");
            })
            ->orWhereHas('categoria', function ($query) {
                $query->where('nombre_categoria', 'LIKE', "%{$this->search}%");
            });


        /* Se verifica si el campo de ordenamiento y la dirección no son nulos y se ordena los datos */
        if ($this->sortField && $this->sortDirection) {
            $books = $books->orderBy($this->sortField, $this->sortDirection);
        } else {
            $this->sortField = null; // Si no hay campo de ordenamiento, no se ordena
            $this->sortDirection = null; // Si no hay dirección de ordenamiento, no se ordena
        }

        /* Se paginan los datos */
        $books = $books->paginate($this->perPage);

        /* Retorna la vista con los datos paginados y ordenados */
        return view('livewire.live-book-table', [
            'books' => $books,

            // Obtiene todos los autores
        ]);
    }


    public function mount()
    {

        $this->icon = $this->iconDirection($this->sortDirection); // Cambia el icono según la dirección de ordenamiento
    }
    public function openBookDetailsModal($mode, $libroId)
    {
        $this->clear();
        /*         $this->resetExcept('search'); // Limpia las variables del componente antes de abrir el modal
 */
        $this->dispatch('openBookDetailsModal', $mode, $libroId); // Dispara el evento para abrir el modal de ejemplares del libro
    }



    public function openModal($mode, $id)
    {
        $this->dispatch('openBookModal', $mode, $id); // Dispara el evento para abrir el modal de creación de categoría
    }

    public function showDeleteConfirmation($rut, $name)
    {
        $this->dispatch('delete-attempt', model: "Book", id: $rut, title: "Eliminando libro", text: "¿Está seguro de eliminar el libro:\n{$name}?\nEsta acción solo la puede deshacer un administrador.");
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
