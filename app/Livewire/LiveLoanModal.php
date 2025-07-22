<?php

namespace App\Livewire;

use App\Models\Ejemplar;
use App\Models\Estudiante;
use App\Models\Libro;
use App\Models\Prestamo;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Exception;
use Livewire\Attributes\Validate;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Livewire\WithFileUploads;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


use function Livewire\Volt\title;

class LiveLoanModal extends Component
{

    use WithFileUploads;
    protected $listeners = [
        'openEditLoanModal' => 'loadLoan',
        'openLoanModal' => 'handleOpenModal',
        'validationAlert' => 'showValidationAlert',
        'deleteLoan' => 'handleDeleteEvent',
        'loanCopy' => 'handleLoanCopy',
        'returnCopy' => 'handleReturnCopy',
        'returnBookCopy' => 'returnBookCopy',
    ];

    public $showModal = false; // Variable para controlar la visibilidad del modal

    public string $mode = ''; // create | edit para verificar el contenido del modal
    /* Datos del ejemplar */
    public $bookCopyBarCode;
    public $bookCopyStatus;

    /* Datos del estudiante */
    public $studentRut;
    public $studentFullName;
    public $studentGrade;


    /* Datos del préstamo */
    public $loan;
    public $loanID;
    public $loanBookCopyID;
    public $loanUserID;
    public $loanDate;
    public $loanReturnDate;
    public $loanStatus;
    public $loanObservations;
    public $loanCreatedBy;
    public $loanDeletedBy;

    public $bookMaxLoanDays;
    public $bookSelectedLoanDays;

    /* Datos anexos */
    public $bookID;
    public $bookTitle;
    public $bookPublisher;
    public $bookAuthorName;
    public $bookDeweyLocation;
    public $bookCoverImage;


    public function render()
    {
        /* Se retorna la vista del modal */
        $bookCopies = Ejemplar::all();
        $users = User::all();

        return view('livewire.live-loan-modal', [
            'bookCopies' => $bookCopies,
            'users' => $users,

        ]);
    }


    public function handleLoanCopy($copyBarCode, $bookID)
    {

        $this->handleOpenModal("create", $bookID);
        $this->bookCopyBarCode = $copyBarCode;
        $this->searchBookCopiesData();
    }

    public function handleReturnCopy($copyBarCode, $bookID)
    {

        $this->handleOpenModal("return", $bookID);
    }

    public function updatedLoanDate()
    {
        $this->calcularFechaDevolucion();
    }

    public function updatedBookSelectedLoanDays()
    {
        $this->calcularFechaDevolucion();
    }

    public function calcularFechaDevolucion()
    {
        // Asegúrate de que $this->bookSelectedLoanDays sea un entero.
        // El casting a (int) convierte la cadena a un número.
        $selectedDays = (int) $this->bookSelectedLoanDays;

        // También es buena práctica verificar que el valor sea válido antes de usarlo.
        // Por ejemplo, si la opción "Selecciona una opción" fuera 0 o vacía.
        if ($this->loanDate && $selectedDays > 0) {
            try {
                $fechaPrestamo = Carbon::parse($this->loanDate);
                // Ahora $selectedDays es definitivamente un entero.
                $this->loanReturnDate = $fechaPrestamo->copy()->addDays($selectedDays)->toDateString();
            } catch (\Exception $e) {
                $this->loanReturnDate = null;
            }
        } else {
            $this->loanReturnDate = null;
        }
    }

    public function createLoan()
    {
        try {
            $this->validate([
                'loanBookCopyID'    => 'required|exists:ejemplares,id_ejemplar',
                'studentRut'    => 'required|string|exists:estudiantes,rut_estudiante',
                'loanDate'          => 'required|date',
                'loanReturnDate'    => 'nullable|date',
                /* 'loanStatus'        => 'required|in:activo,devuelto,retrasado', */
                'loanObservations'  => 'nullable|string|max:65535',
                'loanCreatedBy'     => 'nullable|string|max:255',
                'loanDeletedBy'     => 'nullable|string|max:255',
            ]);
        } catch (ValidationException $e) {
            $messages = implode(', ', $e->validator->errors()->all());
            $this->showValidationAlert($messages);
            return;
        } catch (Exception $e) {
            $this->showValidationAlert("Error inesperado: " . $e->getMessage());

            return;
        }
        $data = [
            'id_ejemplar'      => $this->loanBookCopyID,
            'rut_estudiante'   => $this->studentRut,
            'id_bibliotecario' => Auth::user() ? Auth::user()->id : null, // Asigna el ID del usuario autenticado
            'fecha_prestamo'   => $this->loanDate,
            'fecha_devolucion_esperada' => $this->loanReturnDate,
            'estado'           => 'activo',
            'observaciones'    => $this->loanObservations,
            'created_by'       => Auth::user() ? Auth::user()->name . ' ' . Auth::user()->lastname : '',
        ];

        /* dd($data['rut_estudiante']); */

        try {
            Ejemplar::where('id_ejemplar', $this->loanBookCopyID)->update(['status' => 'prestado']);
            Prestamo::create($data);
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                $this->showValidationAlert('Ya existe un préstamo con estos datos o hay un conflicto de integridad.');
            } else {
                $this->showValidationAlert('Error al guardar el préstamo: ' . $e->getMessage());
            }
            return;
        }
        $this->showSuccessAlert("create", '', '', '');
        $this->closeModal();
        $this->dispatch('refreshLoansTable');
    }

    public function returnBookCopy($idPrestamo, $observaciones = null)
    {
        Log::info("Dentro del metodo returnBookCopy con el id: " . $idPrestamo);
        $prestamo = Prestamo::findOrFail($idPrestamo);
        $ejemplar = Ejemplar::findOrFail($prestamo->id_ejemplar);

        // Fecha actual como devolución real
        $fechaDevolucion = Carbon::now();
        $prestamo->fecha_devolucion_real = $fechaDevolucion;

        // Determinar si la devolución es puntual o retrasada
        if ($prestamo->fecha_devolucion_esperada && $fechaDevolucion->gt(Carbon::parse($prestamo->fecha_devolucion_esperada))) {
            $prestamo->estado = 'devuelto_con_retraso';
        } else {
            $prestamo->estado = 'devuelto_al_dia';
        }

        // Registrar observaciones si se entregan
        if (!is_null($observaciones)) {
            $prestamo->observaciones = $observaciones;
        }

        // Actualizar el ejemplar como disponible
        $ejemplar->status = 'disponible';

        // Guardar cambios
        $prestamo->save();
        $ejemplar->save();

        // Opcionalmente podrías emitir eventos de Livewire aquí si necesitas actualizar la interfaz
        // $this->emit('ejemplarDevuelto', $ejemplar->id_ejemplar);
        $this->dispatch('refreshLoansTable');
    }

    public function updateBookCopy()
    {
        try {
            $this->validate([
                'loanBookCopyID'    => 'required|exists:ejemplares,id_ejemplar',
                'loanStudentRut'    => 'required|string|exists:estudiantes,rut_estudiante',
                'loanLibrarianID'   => 'required|integer|exists:users,id',
                'loanDate'          => 'required|date',
                'loanReturnDate'    => 'nullable|date',
                'loanStatus'        => 'required|in:prestado,devuelto,retrasado',
                'loanObservations'  => 'nullable|string|max:65535',
                'loanCreatedBy'     => 'nullable|string|max:255',
                'loanDeletedBy'     => 'nullable|string|max:255',
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
            'id_ejemplar'      => $this->loanBookCopyID,
            'rut_estudiante'   => $this->loanStudentRut,
            'id_bibliotecario' => $this->loanLibrarianID,
            'fecha_prestamo'   => $this->loanDate,
            'fecha_devolucion_esperada' => $this->loanReturnDate,
            'estado'           => $this->loanStatus,
            'observaciones'    => $this->loanObservations,
            'created_by'       => Auth::user() ? Auth::user()->name . ' ' . Auth::user()->lastname : '',
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

    public function searchBookCopiesData()
    {
        // Limpiar valores anteriores
        $this->reset(['bookTitle', 'bookPublisher', 'bookAuthorName', 'bookCopyStatus']);

        // Buscar el ejemplar junto con su libro y editorial
        try {
            Log::debug("Días maximos de prestamo: " . $this->bookMaxLoanDays);
            $ejemplar = Ejemplar::with(['libro.editorial'])
                ->where('codigo_barras', $this->bookCopyBarCode)

                ->first();

            // Cargar los datos requeridos
            if ($ejemplar->status !== 'disponible') {
                $this->showErrorAlert("Libro no disponible.", "Ejemplar de libro no disponible.", "Esta copia de {$ejemplar->libro->titulo} no está disponible para préstamo");
            } else {
                $this->bookTitle     = $ejemplar->libro->titulo ?? 'Sin título';
                $this->bookPublisher = $ejemplar->libro->editorial->nombre_editorial ?? 'Sin editorial';
                $this->bookCopyStatus    = ucfirst($ejemplar->status) ?? 'Estado no encontrado'; // capitaliza: 'Disponible', etc.
                $this->bookAuthorName = $ejemplar->libro->autor->nombre . ' ' . $ejemplar->libro->autor->apellido_paterno . ' ' . $ejemplar->libro->autor->apellido_materno;
                $this->bookCoverImage = $ejemplar->libro->portada ? asset('storage/' . $ejemplar->libro->portada) : 'Portada no disponible';
                $this->loanBookCopyID = $ejemplar->id_ejemplar ?? null;
                $this->bookMaxLoanDays = $ejemplar->libro->dias_maximos_prestamo ?? null;
            }
        } catch (Exception $e) {
            $this->showValidationAlert("Error al buscar el ejemplar: " . $e->getMessage());
            return;
        }
    }

    public function searchStudentData()
    {
        /* dd($this->studentRut); */
        // Validar el RUT usando Laragear\Rut
        try {
            $rut = \Laragear\Rut\Rut::parse($this->studentRut);
            if (!$rut->isValid()) {
                $this->showValidationAlert('El RUT ingresado no es válido.');
                return;
            }
        } catch (Exception $e) {
            $this->showValidationAlert('Error al validar el RUT: ' . $e->getMessage());
            return;
        }


        // Limpiar valores anteriores
        $this->reset(['studentFullName', 'studentRut', 'studentGrade']);

        // Buscar el ejemplar junto con su libro y editorial
        try {
            $student = Estudiante::where('rut_estudiante', $rut)->first();

            if (!$student) {
                $this->showErrorAlert("", "Estudiante no encontrado", "No se encontró ningún estudiante con el RUT: {$rut}");
                return;
            }

            // Cargar los datos requeridos
            if ($student->estado !== 'activo') {
                $this->showErrorAlert("Estudiante no puede pedir libros", "Estudiante no puede pedir libros", "El estudiante se encuentra {$student->estado}");
            } else {
                $this->studentRut = $student->rut_estudiante ?? 'Sin RUT';
                $this->studentFullName =  $student->nombres . ' ' . $student->apellido_paterno . ' ' . $student->apellido_materno ?? 'Sin nombre';
                $this->studentGrade = $student->curso ?? 'Sin curso';
            }
        } catch (Exception $e) {
            $this->showValidationAlert("Error al buscar el estudiante: " . $e->getMessage());
            return;
        }



        /* if (!$ejemplar) {            
            $this->showValidationAlert("No se encontró ningún ejemplar con ese código de barras.");
        } */



        // Sugerencias extra (opcionales):
        // - $this->bookUbicacion = $ejemplar->ubicacion_estante;
        // - $this->bookISBN = $ejemplar->libro->isbn;
        // - $this->bookAutor = $ejemplar->libro->autor->nombre_completo ?? 'Autor no disponible';
    }

    public function handleOpenModal($mode, $id)
    {
        $this->reset(); // Reinicia todas las variables del componente

        switch ($mode) {
            case 'create':
                $this->loan = null; // Reinicia los datos del usuario
                // Inicializa los selects como vacíos para que muestren el placeholder
                $this->loanID = ''; // Reinicia el ID del libro
                $this->loanStatus = '';
                $this->loanBookCopyID = ''; // Reinicia el ID del ejemplar
                $this->loanUserID = Auth::user() ? Auth::user()->id : null; // Asigna el ID del usuario autenticado
                $this->loanDate = now()->format('Y-m-d'); // Asigna la fecha actual
                break;
            case 'edit':
                $this->reset(); // Reinicia las variables del componente antes de cargar el libro
                $this->loan = Prestamo::findOrFail($id); // Carga el usuario por ID
                $this->loadBook($this->loan); // Carga los datos del usuario seleccionado
                $this->searchStudentData();
                break;
            case 'delete':
                $this->loan = Prestamo::findOrFail($id); // Carga el usuario por ID
                $this->loadBook($this->loan); // Carga los datos del usuario seleccionado
                break;

            case 'return':
                $this->loan = Prestamo::findOrFail($id); // Carga el usuario por ID
                $this->loadBook($this->loan); // Carga los datos del usuario seleccionado
                $this->searchStudentData();

                break;
            default:
                break;
        }
        $this->mode = $mode;
        $this->openModal();
    }

    /* Este método carga los datos cuando el modal se abre en modo EDIT */
    public function loadBook($bookData)
    {
        // Carga los datos del ejemplar y del préstamo en las variables del componente

        // Datos del ejemplar
        $this->loanBookCopyID = $bookData->id_ejemplar ?? null;
        $this->bookID = $bookData->id_libro ?? null;
        $this->bookCopyBarCode = $bookData->codigo_barras ?? null;
        $this->bookDeweyLocation = $bookData->ubicacion_estante ?? null;
        $this->bookCopyStatus = $bookData->status ?? null;
        $this->

            // Si $bookData es un préstamo (Prestamo), también puede tener estos campos:
            $this->studentRut = $bookData->rut_estudiante ?? null;
        $this->loanUserID = $bookData->id_bibliotecario ?? null;
        $this->loanDate = $bookData->fecha_prestamo ?? null;
        $this->loanReturnDate = $bookData->fecha_devolucion_esperada ?? null;
        $this->loanStatus = $bookData->estado ?? null;
        $this->loanObservations = $bookData->observaciones ?? null;
        $this->loanCreatedBy = $bookData->created_by ?? null;
        $this->loanDeletedBy = $bookData->deleted_by ?? null;

        // Datos anexos del ejemplar (si existen)
        /* $this->bookMaxLoanDays = $bookData->dias_maximos_prestamo ?? null;
        $this->bookEntryDate = $bookData->fecha_ingreso ?? null; */
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

    public function showSuccessAlert($typeAlert, $title, $isbn)
    {
        switch ($typeAlert) {
            case 'create':
                $this->dispatch('created-success', model: "Book", title: "Libro registrado", text: "Libro registrado con éxito: $title ISBN: {$isbn}");
                break;
            case 'update':
                $this->dispatch('updated-success', model: "Book", title: "Libro actualizado", text: "Libro actualizado con éxito: $title ISBN: {$isbn}");
                break;
            default:
                break;
        }
    }

    public function showValidationAlert($errors)
    {
        $this->dispatch('validation-alert', errors: $errors);
    }

    public function showErrorAlert($errors, $title, $text)
    {
        $this->dispatch('error-alert', errors: $errors, title: $title, text: $text);
    }

    public function showInfoAlert($title, $text)
    {
        $this->dispatch('info-alert', title: $title, text: $text);
    }
}
