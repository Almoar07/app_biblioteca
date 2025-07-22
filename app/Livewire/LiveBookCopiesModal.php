<?php

namespace App\Livewire;

use App\Models\Ejemplar;
use App\Models\Libro;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Exception;
use Livewire\Attributes\Validate;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Livewire\WithFileUploads;

class LiveBookCopiesModal extends Component
{

    use WithFileUploads;
    protected $listeners = [
        'openEditCopyModal' => 'loadBookCopy',
        'openBookCopyModal' => 'handleOpenModal',
        'validationAlert' => 'showValidationAlert',
        'deleteBookCopy' => 'handleDeleteEvent',
    ];

    public $showModal = false; // Variable para controlar la visibilidad del modal
    public $isLoading = false;

    public string $mode = ''; // create | edit para verificar el contenido del modal
    /* Datos del libro */
    public $bookCopy;
    public $bookID;
    public $bookCopyID;
    public $bookBarCode;
    public $bookDeweyLocation;
    public $bookStatus;
    public $bookLoanDate;
    public $bookReturnDate;
    public $bookMaxLoanDays;
    public $bookEntryDate;
    public $bookTitle;
    public $bookISBN;

    /* Datos del insert */
    public $bookCopyAmount = 1;
    public $copies = [];
    public $barCodeIDs;





    public function render()
    {

        /* Se retorna la vista del modal */
        return view('livewire.live-book-copies-modal', [
            'books' => Libro::orderBy('titulo', 'asc')->get(), // Obtiene todos los libros
        ]);
    }



    public function createBookCopy()
    {
        try {
            $this->validate([
                'bookID' => 'required|exists:libros,id_libro',
                /* 'bookBarCode' => 'required|string|max:255|unique:ejemplares,codigo_barras', */
                'bookDeweyLocation' => 'required|string|max:255',
                'bookStatus' => 'required|string|max:50',
                /* 'bookMaxLoanDays' => 'required|integer|min:1', */
                'bookEntryDate' => 'nullable|date',
            ]);
        } catch (ValidationException $e) {
            $messages = implode(', ', $e->validator->errors()->all());
            $this->showValidationAlert($messages);
            return;
        } catch (Exception $e) {
            $this->showValidationAlert("Error inesperado: " . $e->getMessage());

            return;
        }

        try {
            $batchID = uniqid('batch_');
            for ($i = 0; $i < $this->bookCopyAmount; $i++) {
                $this->copies[] = [
                    'id_libro' => $this->bookID,
                    'codigo_barras' => $this->generateBarCode($this->bookID, $i + 1),
                    'ubicacion_estante' => $this->bookDeweyLocation,
                    'status' => $this->bookStatus,
                    /* 'dias_maximos_prestamo' => $this->bookMaxLoanDays, */
                    'fecha_ingreso' => $this->bookEntryDate,
                    'created_by' => Auth::user() ? Auth::user()->name . ' ' . Auth::user()->lastname : '',
                    'created_at' => now(),
                    'updated_at' => now(),
                    'created_batch' => $batchID,
                ];
            }
            Ejemplar::insert($this->copies);
            $barCodeIDs = Ejemplar::where('created_batch', $batchID)->pluck('id_ejemplar')->toArray();



            $this->dispatch('copias-creadas', barCodeIDs: $barCodeIDs, cantidad: $this->bookCopyAmount);
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) { // Código de error para violación de restricción única
                $this->showValidationAlert('Uno de los códigos de barras generados ya existe en el sistema.');
            } else {
                $this->showValidationAlert('Error al guardar el libro: ' . $e->getMessage());
            }
            return;
        }
        /* $this->showSuccessAlert("Libros registrados", "Se han registrado {$this->bookCopyAmount} copias con éxito."); */
        $this->dispatch('refreshBookCopiesTable');
        $this->closeModal();
    }

    public function searchBookByISBN()
    {
        $this->bookID = Libro::where('isbn', $this->bookISBN)->value('id_libro');
        if ($this->bookID) {
            $this->bookTitle = Libro::where('isbn', $this->bookISBN)->value('titulo');
        }
    }

    /* public function generateBarCode($libroID, $index)
    {
        return '410' . '-' . $libroID . '-' . now()->format('YmdHis') . '-' . $index;
    } */

    public function generateBarCode($libroID, $index)
    {
        $timeCode = strtoupper(base_convert(now()->format('His'), 10, 36)); // hora:minuto:seg en base36
        $bookCode = strtoupper(base_convert($libroID, 10, 36)); // ID del libro en base36

        return "LOCZ{$timeCode}{$bookCode}{$index}";
    }

    public function updateBookCopy()
    {
        try {
            $this->validate([
                /* . $this->originalStudentId . ',rut_estudiante', */
                'bookBarCode' => 'required|string|max:255|unique:ejemplares,codigo_barras,' . $this->bookCopyID . ',id_ejemplar',
                'bookDeweyLocation' => 'required|string|max:255',
                'bookStatus' => 'required|string|max:50',
                'bookLoanDate' => 'nullable|date',
                'bookReturnDate' => 'nullable|date',
                'bookMaxLoanDays' => 'required|integer|min:1',
                'bookEntryDate' => 'nullable|date',
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
        $bookCopy = Ejemplar::findOrFail($this->bookCopyID);
        if (!$bookCopy) {
            $this->showValidationAlert("El ejemplar no existe.");
            return;
        }

        // Mapear las variables a las columnas de la tabla
        $data = [
            'codigo_barras' => $this->bookBarCode,
            'ubicacion_estante' => $this->bookDeweyLocation,
            'status' => $this->bookStatus,
            'fecha_prestamo' => $this->bookLoanDate,
            'fecha_devolucion_esperada' => $this->bookReturnDate,
            /* 'dias_maximos_prestamo' => $this->bookMaxLoanDays, */
            'fecha_ingreso' => $this->bookEntryDate,
            'created_by' => Auth::user() ? Auth::user()->name . ' ' . Auth::user()->lastname : '',
        ];

        $bookCopy->update($data);

        $this->showSuccessAlert("update", $this->bookCopy->titulo, $this->bookCopy->isbn, '');

        $this->closeModal(); // Cierra el modal
        $this->dispatch('refreshBookTable');
    }

    public function handleDeleteEvent($id)
    {
        $this->delete($id);
    }
    public function delete($id)
    {
        try {
            $bookCopy = Ejemplar::findOrFail($id); // Busca el usuario por ID
            $loggedUser = Auth::user();
            $loggedUserName = $loggedUser ? $loggedUser->name : null;
            $loggedUserLastname = $loggedUser ? $loggedUser->lastname : null;
            $bookCopy->deleted_by = $loggedUserName . ' ' . $loggedUserLastname; // Registra quién eliminó al usuario
            /* El usuario pasa estar inactivo cuando se elimina */
            $bookCopy->save(); // Guarda el valor de deleted_by en la base de datos
            $bookCopy->delete(); // Elimina el libro de la base de datos

            $this->dispatch(
                'delete-success',
                model: "book",
                id: $bookCopy->bookCopyID,
                title: "Libro eliminados",
                text: "ID: {$bookCopy->bookCopyID}\n
                Título: {$bookCopy->titulo}\n
                ISBN: {$bookCopy->isbn}"
            );
        } catch (Exception $e) {

            $this->showValidationAlert("Error al eliminar el libro: " . $e->getMessage());
        }

        $this->closeModal(); // Cierra el modal
        $this->dispatch('refreshBookTable');
    }

    public function handleOpenModal($mode, $id)
    {
        $this->reset(); // Reinicia todas las variables del componente

        switch ($mode) {
            case 'create':
                $this->bookCopy = null; // Reinicia los datos del usuario
                // Inicializa los selects como vacíos para que muestren el placeholder
                $this->bookID = ''; // Reinicia el ID del libro
                $this->bookStatus = '';
                $this->bookEntryDate = now()->format('Y-m-d'); // Establece la fecha de ingreso al día actual

                break;
            case 'edit':
                $this->reset(); // Reinicia las variables del componente antes de cargar el libro
                $this->bookCopy = Ejemplar::findOrFail($id); // Carga el usuario por ID
                $this->loadBook($this->bookCopy); // Carga los datos del usuario seleccionado
                break;
            case 'delete':
                $this->bookCopy = Ejemplar::findOrFail($id); // Carga el usuario por ID
                $this->loadBook($this->bookCopy); // Carga los datos del usuario seleccionado
                break;
            default:
                break;
        }
        $this->mode = $mode;
        $this->openModal();
    }

    public function loadBook($bookData)
    {
        // Carga los datos del estudiante en las variables del componente

        $this->bookCopyID = $bookData->id_ejemplar;
        $this->bookID = $bookData->id_libro;
        $this->bookBarCode = $bookData->codigo_barras;
        $this->bookDeweyLocation = $bookData->ubicacion_estante;
        $this->bookStatus = $bookData->status;
        $this->bookLoanDate = $bookData->fecha_prestamo;
        $this->bookReturnDate = $bookData->fecha_devolucion_esperada;
        $this->bookMaxLoanDays = $bookData->dias_maximos_prestamo;
        $this->bookEntryDate = $bookData->fecha_ingreso;
    }

    public function openModal()
    {
        $this->showModal = true; // Cambia la visibilidad del modal a visible


    }
    public function closeModal()
    {
        $this->showModal = false; // Cambia la visibilidad del modal a oculto
        $this->reset(); // Reinicia las variables del modal
    }

    public function showSuccessAlert($title, $text)
    {
        $this->dispatch('success-alert', title: $title, text: $text);
    }

    public function showValidationAlert($errors)
    {
        $this->dispatch('validation-alert', errors: $errors);
    }

    public function showInfoAlert($title, $text)
    {
        $this->dispatch('info-alert', title: $title, text: $text);
    }

    public function showConfirmAlert($event, $title, $text, $id, $confirmButton, $cancelButton)
    {
        $this->dispatch('confirm-alert', title: $title, text: $text, id: $id, confirmButton: $confirmButton, cancelButton: $cancelButton);
    }
}
