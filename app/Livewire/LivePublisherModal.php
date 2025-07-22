<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Editorial;



use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use Illuminate\Database\Eloquent\ModelNotFoundException;

use Illuminate\Validation\ValidationException;


class LivePublisherModal extends Component
{
    protected $listeners = [
        'openEditPublisherModal' => 'loadPublisher',
        'openPublisherModal' => 'handleOpenPublisherModal',
        'validationAlert' => 'showValidationAlert',
        'deletePublisher' => 'handleDeleteEvent',
    ];


    public $showModal = false; // Variable para controlar la visibilidad del modal

    public string $mode = ''; // create | edit para verificar el contenido del modal
    /* Datos del usuario */
    public $publisher;
    public $publisherId;
    public $publisherName;




    public function render()
    {
        return view('livewire.live-publisher-modal');
    }

    public function handleOpenPublisherModal($mode, $id_editorial)
    {

        $this->reset(); // Reinicia las variables del modal

        logger("Se ejectuta el metodo handleOpenPublisherModal con el modo: $mode y el id_autor: $id_editorial");

        switch ($mode) {
            case 'create':
                $this->publisher = null; // Reinicia los datos del usuario
                break;
            case 'edit':
                try {
                    $this->publisher = Editorial::findOrFail($id_editorial);
                } catch (ModelNotFoundException $e) {
                    // Manejar el caso en que no se encuentra el autor
                    logger("No se encontró la editorial con ID: $id_editorial");
                }
                $this->loadPublisher($this->publisher); // Carga los datos del usuario seleccionado
                break;
            default:
                break;
        }
        $this->mode = $mode;
        $this->openModal();
    }
    public function loadPublisher($publisherData)
    {
        $this->publisherId = $publisherData['id_editorial'];
        $this->publisherName = $publisherData['nombre_editorial'];
    }

    public function createPublisher()
    {

        try {
            $this->validate([
                'publisherName' => 'required|string|max:255',

            ]);
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
            'nombre_editorial' => $this->publisherName,
        ];

        Editorial::create($data); // Crear la categoría en la base de datos

        $this->showSuccessAlert("create", $data['nombre_editorial']); // Muestra un mensaje de éxito

        $this->closeModal(); // Cierra el modal
        $this->dispatch('refreshPublisherTable'); // Emite un evento para notificar que el usuario ha sido actualizado

    }


    public function updatePublisher()
    {
        try {
            $this->validate([
                'publisherName' => 'required|string|max:255',
            ]);
        } catch (ValidationException $e) {
            $messages = implode(', ', $e->validator->errors()->all());
            $this->showValidationAlert($messages);
            return;
        } catch (Exception $e) {
            // Errores no relacionados con validación            
            $this->showValidationAlert("Error inesperado: " . $e->getMessage());
            return;
        }


        $data = [
            'nombre_editorial' => $this->publisherName,


        ];
        $publisher = Editorial::findOrFail($this->publisherId);

        $publisher->update($data);


        $this->showSuccessAlert("update", $data['nombre_editorial']); // Muestra un mensaje de éxito
        $this->dispatch('refreshPublisherTable'); // Emite un evento para notificar que el usuario ha sido actualizado

        $this->closeModal(); // Cierra el modal
    }


    public function handleDeleteEvent($id)
    {
        $this->delete($id);
    }

    public function delete($id)
    {
        try {
            $publisher = Editorial::findOrFail($id); // Busca el usuario por ID
            $loggedUser = Auth::user();
            $loggedUserName = $loggedUser ? $loggedUser->name : null;
            $loggedUserLastname = $loggedUser ? $loggedUser->lastname : null;
            $publisher->deleted_by = $loggedUserName . ' ' . $loggedUserLastname; // Registra quién eliminó al usuario
            /* El usuario pasa estar inactivo cuando se elimina */
            $publisher->save(); // Guarda el valor de deleted_by en la base de datos
            $publisher->delete(); // Elimina el publisher de la base de datos

            $this->dispatch(
                'delete-success',
                model: "Publisher",
                id: $publisher->id_editorial,
                title: "Editorial eliminada",
                text: "ID: {$publisher->id_editorial}\n
                Nombre: {$publisher->nombre_editorial}\n"
            );
        } catch (Exception $e) {

            $this->showValidationAlert("Error al eliminar la editorial: " . $e->getMessage());
        }

        $this->closeModal(); // Cierra el modal
        $this->dispatch('refreshPublisherTable');
    }


    public function showSuccessAlert($typeAlert, $name)
    {
        switch ($typeAlert) {
            case 'create':
                $this->dispatch('created-success', model: "Publisher", title: "Editorial creada", text: "Editorial creada con éxito: $name ");
                break;
            case 'update':
                $this->dispatch('updated-success', model: "Publisher", title: "Editorial actualizada", text: "Editorial actualizada con éxito: $name ");
                break;
            default:
                break;
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

                break;
            case 'edit':
                $this->loadPublisher($this->publisher); // Carga los datos del usuario seleccionado
                break;
            default:
                break;
        }
    }

    public function closeModal()
    {
        $this->showModal = false; // Cambia la visibilidad del modal a oculto

    }
}
