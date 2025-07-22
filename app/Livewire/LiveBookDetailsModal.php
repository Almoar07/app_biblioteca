<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Libro; // o el namespace correcto que tengas para Libro
use App\Models\Ejemplar;
use Illuminate\Support\Facades\Log;

class LiveBookDetailsModal extends Component
{
    public $showModal = false;
    public $libroId;
    public $libro;
    public $copiesFound;
    /* public $ejemplares = []; */
    public $mode; // Modo por defecto, puede ser 'sinopsis' u otro según sea necesario


    protected $listeners = ['openBookDetailsModal' => 'openModal', 'clearFilters' => 'clearFilters'];



    public function openModal($mode, $libroId)
    {
        $this->mode = $mode; // <-- ¡Esto es clave!
        switch ($mode) {
            case 'sinopsis':
                $this->libroId = $libroId;
                $this->libro = Libro::findOrFail($libroId); // Cargar el libro para mostrar la sinopsis
                $this->showModal = true;
                break;
            case 'details':
                $this->libroId = $libroId;
                $this->libro = Libro::with('autor', 'editorial', 'categoria')->findOrFail($libroId);
                /* $this->ejemplares = Ejemplar::where('id_libro', $libroId)->get();
                $this->showModal = true;
                break;
            case 'default':
                $this->showModal = false;
                $this->libroId = null;
                $this->libro = null;
                /* $this->ejemplares = []; */
            default:
                break;
        }
    }

    public function clearFilters()
    {
        $this->dispatch('clearDetailsFilters');
    }
    public function closeModal()
    {
        $this->reset(['showModal', 'libroId', 'libro']);
    }

    public function render()
    {
        return view('livewire.live-book-details-modal');
    }
}
