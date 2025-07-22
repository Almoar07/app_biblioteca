<?php

namespace App\Livewire;

use Livewire\Component;

class LiveDeletedUserModal extends Component
{
    protected $listeners = [

        'openDeletedUserModal' => 'handleOpenDeletedUserModal',
        'validationAlert' => 'showValidationAlert',
    ];


    public $showModal = false; // Variable para controlar la visibilidad del modal


    public function render()
    {
        return view('livewire.live-deleted-user-modal');
    }

    public function handleOpenDeletedUserModal()
    {
        $this->reset(); // Reinicia todas las variables del componente
        $this->showModal = true; // Muestra el modal

        $this->openModal();
    }
}
