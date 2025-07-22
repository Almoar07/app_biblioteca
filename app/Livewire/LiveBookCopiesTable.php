<?php

namespace App\Livewire;

use App\Models\Ejemplar;
use Livewire\{Component, WithPagination, WithoutUrlPagination};
use App\Models\Libro;
use App\Models\Prestamo;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use function Livewire\Volt\js;

class LiveBookCopiesTable extends Component
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
        'refreshBookCopiesTable' => '$refresh',
        'successEditingBook' => 'showSuccessAlert',
        'showDeleteMessage' => 'showDeleteMessage',
        'refreshTable' => '$refresh',



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
        $bookCopies = DB::table('ejemplares')
            ->join('libros', 'ejemplares.id_libro', '=', 'libros.id_libro')
            ->select(
                'ejemplares.id_ejemplar',
                'ejemplares.codigo_barras',
                'ejemplares.status',
                'ejemplares.fecha_ingreso',
                'ejemplares.ubicacion_estante',
                /* 'ejemplares.dias_maximos_prestamo', */
                'libros.id_libro',
                'libros.titulo as titulo_libro',
                'libros.portada'
            )
            ->where(function ($query) {
                $query->where('ejemplares.codigo_barras', 'LIKE', "%{$this->search}%")
                    ->orWhere('ejemplares.ubicacion_estante', 'LIKE', "%{$this->search}%")
                    ->orWhere('ejemplares.status', 'LIKE', "%{$this->search}%")
                    /* ->orWhere('ejemplares.fecha_prestamo', 'LIKE', "%{$this->search}%")
                    ->orWhere('ejemplares.fecha_devolucion_esperada', 'LIKE', "%{$this->search}%") */
                    /* ->orWhere('ejemplares.dias_maximos_prestamo', 'LIKE', "%{$this->search}%") */
                    ->orWhere('ejemplares.fecha_ingreso', 'LIKE', "%{$this->search}%")
                    ->orWhere('libros.titulo', 'LIKE', "%{$this->search}%")
                    ->orWhere('libros.isbn', 'LIKE', "%{$this->search}%");
            });

        if ($this->sortField && $this->sortDirection) {
            $bookCopies = $bookCopies->orderBy($this->sortField, $this->sortDirection);
        } else {
            $bookCopies = $bookCopies->orderBy('ejemplares.id_ejemplar', 'asc');
        }

        $bookCopies = $bookCopies->paginate($this->perPage);

        /* Retorna la vista con los datos paginados y ordenados */
        return view('livewire.live-book-copies-table', [
            'bookCopies' => $bookCopies,

            // Obtiene todos los autores
        ]);
    }

    public function loanBookCopy($copyBarCode, $bookID)
    {
        $this->dispatch('loanCopy', $copyBarCode, $bookID);
        Log::info('Se disparo el evento loanCopy que abre el modal de prestamo de ejemplar');
    }
    public function returnBookCopy($copyBarCode, $bookCopyID)
    {
        Log::info('Se disparo el evento returnBookCopy con el código de barras: ' . $copyBarCode);
        // 1. Buscar el ejemplar por código de barras
        try {
            Log::info('Buscando el ejemplar con el ID: ' . $bookCopyID);
            $ejemplar = Ejemplar::where('codigo_barras', $copyBarCode)
                ->where('id_ejemplar', $bookCopyID)
                ->firstOrFail();
        } catch (\Exception $e) {
            Log::error('Error al buscar el ejemplar: ' . $e->getMessage());
            return;
        }
        Log::info('Se encontró el ejemplar con el código de barras: ' . $copyBarCode);

        // 2. Buscar el préstamo activo de ese ejemplar
        try {
            $prestamo = Prestamo::where('id_ejemplar', $ejemplar->id_ejemplar)
                ->where('estado', 'activo')->orWhere('estado', 'retrasado')
                ->firstOrFail();
        } catch (\Exception $e) {
            Log::error('Error al buscar el préstamo activo: ' . $e->getMessage());
            return;
        }
        Log::info('Se encontró el préstamo activo de ese ejemplar');

        // 3. Verificar si está retrasado o no
        $hoy = now();
        $fechaDevolucion = $prestamo->fecha_devolucion;

        if ($hoy > $fechaDevolucion) {
            $prestamo->estado = 'devuelto_con_retraso';
        } else {
            $prestamo->estado = 'devuelto_al_dia';
        }

        $prestamo->fecha_devolucion_real = now();

        // 4. Guardar cambios en el préstamo
        $prestamo->save();

        // 5. Marcar ejemplar como disponible
        $ejemplar->status = 'disponible';
        $ejemplar->save();
    }

    public function mount()
    {

        $this->icon = $this->iconDirection($this->sortDirection); // Cambia el icono según la dirección de ordenamiento
    }
    public function openBookDetailsModal($mode, $libroId)
    {
        $this->clear(); // Limpia las variables del componente antes de abrir el modal
        $this->dispatch('openBookDetailsModal', $mode, $libroId); // Dispara el evento para abrir el modal de ejemplares del libro
    }

    public function openModal($mode, $id)
    {
        $this->dispatch('openBookCopyModal', $mode, $id); // Dispara el evento para abrir el modal de creación de categoría
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
