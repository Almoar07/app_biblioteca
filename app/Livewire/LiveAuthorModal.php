<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Autor;



use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use Illuminate\Database\Eloquent\ModelNotFoundException;

use Illuminate\Validation\ValidationException;


class LiveAuthorModal extends Component
{
    protected $listeners = [
        'openEditAuthorModal' => 'loadAuthor',
        'openAuthorModal' => 'handleOpenAuthorModal',
        'validationAlert' => 'showValidationAlert',
        'deleteAuthor' => 'handleDeleteAuthorEvent',
    ];


    public $showModal = false; // Variable para controlar la visibilidad del modal

    public string $mode = ''; // create | edit para verificar el contenido del modal
    /* Datos del usuario */
    public $author;
    public $authorId;
    public $authorName;
    public $authorLastname;
    public $authorLastname2;
    public $authorNationality;
    public $authorBirthday;
    public $liveAuthorTable;
    public array $countries = [];

    public function mount()
    {


        $json_countries = file_get_contents(public_path('data/countries.json'));
        $this->countries = json_decode($json_countries, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            logger('Error decoding JSON: ' . json_last_error_msg());
        } else {
            logger("El archivo countries.json se ha cargado correctamente.");
            logger("Los datos de los paises son: " . json_encode($this->countries));
        }
    }
    public function render()
    {
        return view('livewire.live-author-modal');
    }

    public function handleOpenAuthorModal($mode, $id_autor)
    {

        $this->resetExcept('countries'); // resetea todas las variables excepto 'countries'

        logger("Se ejectuta el metodo handleOpenAuthorModal con el modo: $mode y el id_autor: $id_autor");

        switch ($mode) {
            case 'create':
                $this->author = null; // Reinicia los datos del usuario
                $this->authorNationality = ''; // Reinicia la nacionalidad del autor
                break;
            case 'edit':
                try {
                    $this->author = Autor::findOrFail($id_autor);
                } catch (ModelNotFoundException $e) {
                    // Manejar el caso en que no se encuentra el autor
                    logger("No se encontró el autor con ID: $id_autor");
                }
                $this->loadAuthor($this->author); // Carga los datos del usuario seleccionado
                break;
            default:
                break;
        }
        $this->mode = $mode;
        $this->openModal();
    }
    public function loadAuthor($authorData)
    {
        $this->authorId = $authorData['id_autor'];
        $this->authorName = $authorData['nombre'];
        $this->authorLastname = $authorData['apellido_paterno'];
        $this->authorLastname2 = $authorData['apellido_materno'];
        $this->authorNationality = $authorData['nationalidad'];
        $this->authorBirthday = $authorData['fecha_nacimiento'];
    }

    public function createAuthor()
    {

        try {
            $this->validate([
                'authorName' => 'required|string|max:255',
                'authorLastname' => 'required|string|max:255',
                'authorLastname2' => 'nullable|string|max:255',
                'authorNationality' => 'required|string|max:255',
                'authorBirthday' => 'required|date',
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
            'nombre' => $this->authorName,
            'apellido_paterno' => $this->authorLastname,
            'apellido_materno' => $this->authorLastname2,
            'nacionalidad' => $this->authorNationality,
            'fecha_nacimiento' => $this->authorBirthday,
        ];

        Autor::create($data); // Crear el nuevo usuario

        $this->showSuccessAlert("create", $data['nombre'], $data['apellido_paterno'], $data['apellido_materno']); // Muestra un mensaje de éxito

        $this->closeModal(); // Cierra el modal
        $this->dispatch('refreshAuthorTable'); // Emite un evento para notificar que el usuario ha sido actualizado

    }


    public function updateAuthor()
    {
        try {
            $this->validate([
                'authorName' => 'required|string|max:255',
                'authorLastname' => 'required|string|max:255',
                'authorLastname2' => 'nullable|string|max:255',
                'authorNationality' => 'required|string|max:255',
                'authorBirthday' => 'required|date',
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
            'nombre' => $this->authorName,
            'apellido_paterno' => $this->authorLastname,
            'apellido_materno' => $this->authorLastname2,
            'nacionalidad' => $this->authorNationality,
            'fecha_nacimiento' => $this->authorBirthday,
        ];
        $author = Autor::findOrFail($this->authorId);

        $author->update($data);


        $this->showSuccessAlert("update", $data['nombre'], $data['apellido_paterno'], $data['apellido_materno']); // Muestra un mensaje de éxito
        $this->dispatch('refreshAuthorTable'); // Emite un evento para notificar que el usuario ha sido actualizado

        $this->closeModal(); // Cierra el modal
    }


    public function handleDeleteAuthorEvent($id)
    {
        $this->deleteAuthor($id);
    }

    public function deleteAuthor($id)
    {
        try {
            $author = Autor::findOrFail($id); // Busca el usuario por ID
            $loggedUser = Auth::user();
            $loggedUserName = $loggedUser ? $loggedUser->name : null;
            $loggedUserLastname = $loggedUser ? $loggedUser->lastname : null;
            $author->deleted_by = $loggedUserName . ' ' . $loggedUserLastname; // Registra quién eliminó al usuario
            /* El usuario pasa estar inactivo cuando se elimina */
            $author->save(); // Guarda el valor de deleted_by en la base de datos
            $author->delete(); // Elimina el author de la base de datos

            $this->dispatch(
                'delete-success',
                model: "student",
                id: $author->id_autor,
                title: "Autor eliminados",
                text: "ID: {$author->id_autor}\n
                Nombre: {$author->nombre} {$author->apellido_paterno} {$author->apellido_materno}\n"
            );
        } catch (Exception $e) {

            $this->showValidationAlert("Error al eliminar el autor: " . $e->getMessage());
        }

        $this->closeModal(); // Cierra el modal
        $this->dispatch('refreshAuthorTable');
    }


    public function showSuccessAlert($typeAlert, $name, $lastname, $lastname2)
    {
        switch ($typeAlert) {
            case 'create':
                $this->dispatch('created-success', model: "author", title: "Autor creado", text: "Autor creado con éxito: $name $lastname $lastname2");
                break;
            case 'update':
                $this->dispatch('updated-success', model: "author", title: "Autor actualizado", text: "Autor actualizado con éxito: $name $lastname $lastname2");
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
                $this->loadAuthor($this->author); // Carga los datos del usuario seleccionado
                break;
            default:
                break;
        }
    }

    public function closeModal()
    {
        $this->showModal = false; // Cambia la visibilidad del modal a oculto
        $this->resetExcept('countries'); // Reinicia las variables del modal excepto 'countries'');
    }
}
