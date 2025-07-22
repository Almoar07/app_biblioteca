<?php

namespace App\Livewire;

use App\Models\Ejemplar;
use Livewire\{Component, WithPagination, WithoutUrlPagination};
use App\Models\Prestamo;
use App\Models\Libro;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class LiveLoanTable extends Component
{
    use WithPagination;

    public $search = ''; // Variable para almacenar el texto de búsqueda
    public $perPage = 5; // Número de usuarios por página
    public $sortField = null; // Campo por el que se ordena
    public $sortDirection = null; // Dirección de ordenamiento
    public $icon = '-circle'; // Icono de ordenamiento
    public $showModal = 'hidden';
    public $loanByID;


    /* Variables para la gestion del usuario a editar o eliminar*/
    public $selectedLoanId;
    public $selectedLoan;
    protected $listeners = [
        'refreshLoansTable' => '$refresh',
        'successEditingLoan' => 'showSuccessAlert',
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
        $loansQuery = DB::table('prestamos')
            ->join('ejemplares', 'prestamos.id_ejemplar', '=', 'ejemplares.id_ejemplar')
            ->join('estudiantes', 'prestamos.rut_estudiante', '=', 'estudiantes.rut_estudiante')
            ->join('users', 'prestamos.id_bibliotecario', '=', 'users.id')
            ->join('libros', 'ejemplares.id_libro', '=', 'libros.id_libro')
            ->select(
                'prestamos.*',
                'ejemplares.codigo_barras',
                'ejemplares.status as status_ejemplar',
                'libros.titulo as titulo_libro',
                'libros.portada as portada_libro',
                'estudiantes.nombres as nombre_estudiante',
                'estudiantes.apellido_paterno as apellido_paterno_estudiante',
                'estudiantes.apellido_materno as apellido_materno_estudiante',
                'users.name as nombre_bibliotecario',
                'users.lastname as apellido_paterno_bibliotecario',
                'users.lastname2 as apellido_materno_bibliotecario'
            );

        // 🧠 Aplica filtro por ID si está definido
        if (!empty($this->loanByID)) {
            $loansQuery->where('prestamos.id_prestamo', '=', $this->loanByID);
        } else {
            // 🔍 Filtro de búsqueda libre
            $loansQuery->where(function ($query) {
                $query->where('ejemplares.codigo_barras', 'LIKE', "%{$this->search}%")
                    ->orWhere('prestamos.estado', 'LIKE', "%{$this->search}%")
                    ->orWhere('libros.titulo', 'LIKE', "%{$this->search}%")
                    ->orWhere('estudiantes.nombres', 'LIKE', "%{$this->search}%")
                    ->orWhere('estudiantes.apellido_paterno', 'LIKE', "%{$this->search}%")
                    ->orWhere('estudiantes.apellido_materno', 'LIKE', "%{$this->search}%")
                    ->orWhere('users.name', 'LIKE', "%{$this->search}%")
                    ->orWhere('users.lastname', 'LIKE', "%{$this->search}%");
            });
        }

        // ⚖️ Ordenamiento
        if ($this->sortField && $this->sortDirection) {
            $loansQuery->orderBy($this->sortField, $this->sortDirection);
        } else {
            $loansQuery->orderBy('ejemplares.id_ejemplar', 'asc');
        }

        // 📄 Paginación
        $loans = $loansQuery->paginate($this->perPage);

        return view('livewire.live-loan-table', [
            'loans' => $loans,
        ]);
    }





    public function mount()
    {

        $this->icon = $this->iconDirection($this->sortDirection); // Cambia el icono según la dirección de ordenamiento
    }
    public function openDetailsModal($mode, $libroId)
    {
        $this->clear(); // Limpia las variables del componente antes de abrir el modal
        $this->dispatch('openBookDetailsModal', $mode, $libroId); // Dispara el evento para abrir el modal de ejemplares del libro

    }


    public function returnBookCopy($id_prestamo)
    {
        Log::info('Se disparo el evento returnBookCopy con el id: ' . $id_prestamo);
        $this->dispatch('returnBookCopy', $id_prestamo);
    }

    public function openModal($mode, $id)
    {
        $this->dispatch('openLoanModal', $mode, $id); // Dispara el evento para abrir el modal de creación de categoría
    }

    public function showDeleteConfirmation($rut, $name)
    {
        $this->dispatch('delete-attempt', model: "Book", id: $rut, title: "Eliminando libro", text: "¿Está seguro de eliminar el libro:\n{$name}?\nEsta acción solo la puede deshacer un administrador.");
    }

    public function clear()
    {
        $this->reset();
        return redirect()->route('administracion.prestamos');
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
