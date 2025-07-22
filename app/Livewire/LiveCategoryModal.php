<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Categoria;



use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use Illuminate\Database\Eloquent\ModelNotFoundException;

use Illuminate\Validation\ValidationException;


class LiveCategoryModal extends Component
{
    protected $listeners = [
        'openEditCategoryModal' => 'loadCategory',
        'openCategoryModal' => 'handleOpenCategoryModal',
        'validationAlert' => 'showValidationAlert',
        'deleteCategory' => 'handleDeleteEvent',
    ];


    public $showModal = false; // Variable para controlar la visibilidad del modal

    public string $mode = ''; // create | edit para verificar el contenido del modal
    /* Datos del usuario */
    public $category;
    public $categoryId;
    public $categoryName;
    public $categoryDescription;



    public function render()
    {
        return view('livewire.live-category-modal');
    }

    public function handleOpenCategoryModal($mode, $id_category)
    {

        $this->reset(); // Reinicia las variables del modal

        logger("Se ejectuta el metodo handleOpenCategoryModal con el modo: $mode y el id_autor: $id_category");

        switch ($mode) {
            case 'create':
                $this->category = null; // Reinicia los datos del usuario
                break;
            case 'edit':
                try {
                    $this->category = Categoria::findOrFail($id_category);
                } catch (ModelNotFoundException $e) {
                    // Manejar el caso en que no se encuentra el autor
                    logger("No se encontró la categoría con ID: $id_category");
                }
                $this->loadCategory($this->category); // Carga los datos del usuario seleccionado
                break;
            default:
                break;
        }
        $this->mode = $mode;
        $this->openModal();
    }
    public function loadCategory($categoryData)
    {
        $this->categoryId = $categoryData['id_categoria'];
        $this->categoryName = $categoryData['nombre_categoria'];
        $this->categoryDescription = $categoryData['descripcion_categoria'];
    }

    public function createCategory()
    {

        try {
            $this->validate([
                'categoryName' => 'required|string|max:255',
                'categoryDescription' => 'required|string|max:255',

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
            'nombre_categoria' => $this->categoryName,
            'descripcion_categoria' => $this->categoryDescription,

        ];

        Categoria::create($data); // Crear la categoría en la base de datos

        $this->showSuccessAlert("create", $data['nombre_categoria']); // Muestra un mensaje de éxito

        $this->closeModal(); // Cierra el modal
        $this->dispatch('refreshCategoryTable'); // Emite un evento para notificar que el usuario ha sido actualizado

    }


    public function updateCategory()
    {
        try {
            $this->validate([
                'categoryName' => 'required|string|max:255',
                'categoryDescription' => 'required|string|max:255',

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
            'nombre_categoria' => $this->categoryName,
            'descripcion_categoria' => $this->categoryDescription,

        ];
        $category = Categoria::findOrFail($this->categoryId);

        $category->update($data);


        $this->showSuccessAlert("update", $data['nombre_categoria']); // Muestra un mensaje de éxito
        $this->dispatch('refreshCategoryTable'); // Emite un evento para notificar que el usuario ha sido actualizado

        $this->closeModal(); // Cierra el modal
    }


    public function handleDeleteEvent($id)
    {
        $this->delete($id);
    }

    public function delete($id)
    {
        try {
            $category = Categoria::findOrFail($id); // Busca el usuario por ID
            $loggedUser = Auth::user();
            $loggedUserName = $loggedUser ? $loggedUser->name : null;
            $loggedUserLastname = $loggedUser ? $loggedUser->lastname : null;
            $category->deleted_by = $loggedUserName . ' ' . $loggedUserLastname; // Registra quién eliminó al usuario
            /* El usuario pasa estar inactivo cuando se elimina */
            $category->save(); // Guarda el valor de deleted_by en la base de datos
            $category->delete(); // Elimina el category de la base de datos

            $this->dispatch(
                'delete-success',
                model: "Category",
                id: $category->id_categoria,
                title: "Categoría eliminadas",
                text: "ID: {$category->id_categoria}\n
                Nombre: {$category->nombre_categoria}\n"
            );
        } catch (Exception $e) {

            $this->showValidationAlert("Error al eliminar la categoría: " . $e->getMessage());
        }

        $this->closeModal(); // Cierra el modal
        $this->dispatch('refreshCategoryTable');
    }


    public function showSuccessAlert($typeAlert, $name)
    {
        switch ($typeAlert) {
            case 'create':
                $this->dispatch('created-success', model: "author", title: "Categoria creada", text: "Categoria creada con éxito: $name ");
                break;
            case 'update':
                $this->dispatch('updated-success', model: "author", title: "Categoria actualizada", text: "Categoria actualizada con éxito: $name ");
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
                $this->loadCategory($this->category); // Carga los datos del usuario seleccionado
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
