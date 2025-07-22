<?php

namespace App\Livewire;

use App\Models\Estudiante;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Exception;
use Livewire\Attributes\Validate;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;

class LiveStudentModal extends Component
{

    protected $listeners = [
        'openEditUserModal' => 'loadUser',
        'openStudentModal' => 'handleOpenStudentModal',
        'validationAlert' => 'showValidationAlert',
        'deleteStudent' => 'handleDeleteStudentEvent',
    ];

    public $showModal = false; // Variable para controlar la visibilidad del modal

    public string $mode = ''; // create | edit para verificar el contenido del modal
    /* Datos del usuario */
    public $student;
    public $studentId;
    public $originalStudentId; // Para almacenar el RUT original antes de la edición
    public $studentName;
    public $studentLastname;
    public $studentLastname2;
    public $studentBirthday;
    public $studentAddress;
    public $studentMunicipalityId;
    public $studentCourse;
    public $studentEmail;
    public $studentPhone;
    public $studentStatus;
    public $studentGrade;
    public $studentLetter;



    public function render()
    {
        // Carga todas las municipalidades disponibles
        $municipalities = $this->getAllMunicipalities();
        // Pasa las municipalidades a la vista
        return view('livewire.live-student-modal', [
            'municipalities' => $municipalities,
        ]);
    }

    public function getAllMunicipalities()
    {
        return \App\Models\Comuna::all();
    }

    public function createStudent()
    {

        try {
            $this->validate([
                'studentName' => 'required|string|max:255',
                'studentLastname' => 'required|string|max:255',
                'studentLastname2' => 'nullable|string|max:255',
                'studentBirthday' => 'required|date',
                'studentAddress' => 'required|string|max:255',
                'studentMunicipalityId' => 'required|exists:comunas,id_comuna',
                'studentCourse' => 'required|string|max:255',
                'studentEmail' => 'required|email|max:255|unique:users,email',
                'studentPhone' => 'nullable|string|max:15',
                'studentStatus' => 'required|string|in:activo,inactivo,egresado,bloqueado',
            ]);
            // Validar el RUT usando Laragear\Rut
            $rut = \Laragear\Rut\Rut::parse($this->studentId);
            if (!$rut->isValid()) {
                $this->showValidationAlert('El RUT ingresado no es válido.');
                return;
            }
            // Normalizar el RUT (formato con puntos y guion)
            $this->studentId = $rut->format();
        } catch (ValidationException $e) {
            $messages = implode(', ', $e->validator->errors()->all());
            $this->showValidationAlert($messages);
            return;
        } catch (Exception $e) {
            $this->showValidationAlert("Error inesperado: " . $e->getMessage());
            return;
        }

        $data = [
            'rut_estudiante' => $this->studentId,
            'nombres' => ucfirst($this->studentName),
            'apellido_paterno' => ucfirst($this->studentLastname),
            'apellido_materno' => ucfirst($this->studentLastname2),
            'fecha_nacimiento' => $this->studentBirthday,
            'direccion' => $this->studentAddress,
            'comuna_estudiante' => $this->studentMunicipalityId,
            'curso' => $this->studentCourse,
            'estado' => $this->studentStatus,
            'email' => $this->studentEmail,
            'telefono' => $this->studentPhone,
            'created_by' => Auth::user() ? Auth::user()->name . ' ' . Auth::user()->lastname : 'formulario de registro',
        ];

        try {
            Estudiante::create($data);
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) { // Código de error para violación de restricción única
                $this->showValidationAlert('El RUT ingresado ya existe en el sistema.');
            } else {
                $this->showValidationAlert('Error al guardar el estudiante: ' . $e->getMessage());
            }
            return;
        }

        $this->dispatch("success-alert", title: "Estudiante registrado", text: "Estudiante registrado con éxito.");
        $this->closeModal();
        $this->dispatch('refreshStudentTable');
    }

    public function updateStudent()
    {
        try {
            $this->validate([
                'studentId' => 'required|string|max:255|unique:estudiantes,rut_estudiante,' . $this->originalStudentId . ',rut_estudiante',
                'studentName' => 'required|string|max:255',
                'studentLastname' => 'required|string|max:255',
                'studentLastname2' => 'nullable|string|max:255',
                'studentBirthday' => 'required|date',
                'studentAddress' => 'required|string|max:255',
                'studentMunicipalityId' => 'required|exists:comunas,id_comuna',
                'studentCourse' => 'required|string|max:255',
                'studentEmail' => 'required|email|max:255|unique:users,email',
                'studentPhone' => 'nullable|string|max:15',
                'studentStatus' => 'required|string|in:activo,inactivo,egresado,bloqueado',
            ]);
            // Validar el RUT usando Laragear\Rut
            $rut = \Laragear\Rut\Rut::parse($this->studentId);
            if (!$rut->isValid()) {
                $this->showValidationAlert('El RUT ingresado no es válido.');
                return;
            }
            // Normalizar el RUT (formato con puntos y guion)
            $this->studentId = $rut->format();
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
            'rut_estudiante' => $this->studentId,
            'nombres' => $this->studentName,
            'apellido_paterno' => $this->studentLastname,
            'apellido_materno' => $this->studentLastname2,
            'fecha_nacimiento' => $this->studentBirthday,
            'direccion' => $this->studentAddress,
            'comuna_estudiante' => $this->studentMunicipalityId,
            'curso' => $this->studentCourse,
            'estado' => $this->studentStatus,
            'email' => $this->studentEmail,
            'telefono' => $this->studentPhone,
            'created_by' => Auth::user() ? 'Actualizado por: ' .  Auth::user()->name . ' ' . Auth::user()->lastname : 'formulario de registro',
        ];
        $student = Estudiante::findOrFail($this->originalStudentId);

        $student->update($data);


        $this->showSuccessAlert("update", $data['nombres'], $data['apellido_paterno'], $data['apellido_materno']); // Muestra un mensaje de éxito

        $this->closeModal(); // Cierra el modal
        $this->dispatch('refreshStudentTable');
    }

    public function handleDeleteStudentEvent($id)
    {
        $this->deleteStudent($id);
    }
    public function deleteStudent($id)
    {
        try {
            $student = Estudiante::findOrFail($id); // Busca el usuario por ID
            $loggedUser = Auth::user();
            $loggedUserName = $loggedUser ? $loggedUser->name : null;
            $loggedUserLastname = $loggedUser ? $loggedUser->lastname : null;
            $student->deleted_by = $loggedUserName . ' ' . $loggedUserLastname; // Registra quién eliminó al usuario
            /* El usuario pasa estar inactivo cuando se elimina */
            $student->estado = 'inactivo'; // Cambia el estado del usuario a inactivo
            $student->save(); // Guarda el valor de deleted_by en la base de datos
            $student->delete(); // Elimina el estudiante de la base de datos

            $this->dispatch(
                'delete-success',
                model: "student",
                id: $student->rut_estudiante,
                title: "Estudiante eliminado",
                text: "RUT: {$student->rut_estudiante}\n
                Nombre: {$student->nombres} {$student->apellido_paterno} {$student->apellido_materno}\n"
            );
        } catch (Exception $e) {

            $this->showValidationAlert("Error al eliminar el estudiante: " . $e->getMessage());
        }

        $this->closeModal(); // Cierra el modal
        $this->dispatch('refreshStudentTable');
    }

    public function handleOpenStudentModal($mode, $id)
    {
        $this->reset(); // Reinicia todas las variables del componente

        switch ($mode) {
            case 'create':
                $this->student = null; // Reinicia los datos del usuario
                // Inicializa la comuna como vacía para que el select muestre el placeholder
                $this->studentMunicipalityId = '';
                $this->studentGrade = ''; // Reinicia el grado del estudiante
                $this->studentLetter = ''; // Reinicia la letra del estudiante
                $this->studentStatus = ''; // Establece el estado por defecto como activo
                break;
            case 'edit':
                $this->student = Estudiante::findOrFail($id); // Carga el usuario por ID
                $this->loadStudent($this->student); // Carga los datos del usuario seleccionado
                break;
            case 'delete':
                $this->student = Estudiante::findOrFail($id); // Carga el usuario por ID
                $this->loadStudent($this->student); // Carga los datos del usuario seleccionado
                break;
            default:
                break;
        }
        $this->mode = $mode;
        $this->openModal();
    }

    public function updated($propertyName)
    {
        // Si se actualiza el grado o la letra, actualiza el curso combinado
        if (in_array($propertyName, ['studentGrade', 'studentLetter'])) {
            $this->studentCourse = trim(($this->studentGrade ?? '') . ' ' . ($this->studentLetter ?? ''));
        }
    }

    public function loadStudent($studentData)
    {
        // Carga los datos del estudiante en las variables del componente
        $this->originalStudentId = $studentData['rut_estudiante'];
        $this->studentId = $studentData['rut_estudiante'];
        $this->studentName = $studentData['nombres'];
        $this->studentLastname = $studentData['apellido_paterno'];
        $this->studentLastname2 = $studentData['apellido_materno'];
        $this->studentBirthday = $studentData['fecha_nacimiento'];
        $this->studentAddress = $studentData['direccion'];
        $this->studentMunicipalityId = $studentData['comuna_estudiante'];
        $this->studentCourse = $studentData['curso'];
        $this->studentEmail = $studentData['email'];
        $this->studentPhone = $studentData['telefono'];
        $this->studentStatus = $studentData['estado'];

        // Separar el curso en grado y letra si es posible
        if (!empty($studentData['curso'])) {
            $parts = explode(' ', $studentData['curso'], 2);
            $this->studentGrade = $parts[0] ?? null;
            $this->studentLetter = $parts[1] ?? null;
        } else {
            $this->studentGrade = null;
            $this->studentLetter = null;
        }
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

    public function showSuccessAlert($typeAlert, $name, $lastname, $lastname2)
    {
        switch ($typeAlert) {
            case 'create':
                $this->dispatch('created-success', model: "Student", title: "Estudiante registrado", text: "Estudiante registrado con éxito: $name $lastname $lastname2");
                break;
            case 'update':
                $this->dispatch('updated-success', model: "Student", title: "Estudiante actualizado", text: "Estudiante actualizado con éxito: {$name} {$lastname} {$lastname2}");
                break;
            default:
                break;
        }
    }

    public function showValidationAlert($errors)
    {
        $this->dispatch('validation-alert', errors: $errors);
    }
}
