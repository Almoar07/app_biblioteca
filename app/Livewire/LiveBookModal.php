<?php

namespace App\Livewire;

use App\Models\Libro;
use App\Models\Autor;
use App\Models\Editorial;
use App\Models\Categoria;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Exception;
use Livewire\Attributes\Validate;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class LiveBookModal extends Component
{

    use WithFileUploads;
    protected $listeners = [
        'openEditBookModal' => 'loadBook',
        'openBookModal' => 'handleOpenModal',
        'validationAlert' => 'showValidationAlert',
        'deleteBook' => 'handleDeleteEvent',
    ];

    public $showModal = false; // Variable para controlar la visibilidad del modal

    public string $mode = ''; // create | edit para verificar el contenido del modal
    /* Datos del libro */
    public $book;
    public $bookID;
    public $bookTitle;
    public $bookISBN;
    public $bookIDAuthor;
    public $bookIDPublisher;
    public $bookYearPublication;
    public $bookIDCategory;
    public $bookSynopsis;
    public $bookCover; // Imagen de la portada del libro que sube el usuario
    public $bookMaxLoanDays;

    // Para sugerencias
    public $autoresSugeridos = [];
    public $editorialesSugeridas = [];
    public $categoriasSugeridas = [];
    public $bookAuthorName; // Nombre del autor para previsualizar
    public $bookYear; // Año de publicación para previsualizar
    public $bookPublisherName; // Nombre de la editorial para previsualizar
    public $bookCategoryName; // Nombre de la categoría para previsualizar
    public $bookCoverPreview; // URL de la portada para previsualizar

    //Alternar entre input y select
    public $showAuthorSelect = false;
    public $showPublisherSelect = false;
    public $showCategorySelect = false;
    public $authorReadOnly = false;
    public $publisherReadOnly = false;
    public $categoryReadOnly = false;






    public function render()
    {
        // Obtiene los datos relacionados con el libro
        $bookRelatedData = $this->getBookRelatedData();
        /* Se retorna la vista del modal */
        return view('livewire.live-book-modal', [
            'authors' => $bookRelatedData['authors'],
            'publishers' => $bookRelatedData['publishers'],
            'categories' => $bookRelatedData['categories'],
        ]);
    }

    public function getBookRelatedData()
    {
        return [
            'authors' => \App\Models\Autor::all(),
            'publishers' => \App\Models\Editorial::all(),
            'categories' => \App\Models\Categoria::all(),
        ];
    }

    public function createBook()
    {
        $author = Autor::firstOrCreate([
            'nombre' => ucwords(strtolower(trim($this->bookAuthorName)))
        ]);

        $editorial = Editorial::firstOrCreate([
            'nombre_editorial' => ucwords(strtolower(trim($this->bookPublisherName)))
        ]);

        $category = Categoria::firstOrCreate([
            'nombre_categoria' => ucwords(strtolower(trim($this->bookCategoryName)))
        ]);
        // Asignar los IDs de autor, editorial y categoría
        $this->bookIDCategory = $category->id_categoria;
        $this->bookIDAuthor = $author->id_autor;
        $this->bookIDPublisher = $editorial->id_editorial;

        try {
            $this->validate([
                'bookTitle' => 'required|string|max:255',
                'bookISBN' => [
                    'required',
                    'string',
                    'regex:/^\d{10}(\d{3})?$/',
                    'unique:libros,isbn'
                ],
                'bookIDAuthor' => 'required|exists:autores,id_autor',
                'bookIDPublisher' => 'required|exists:editoriales,id_editorial',
                'bookYearPublication' => 'nullable|digits:4',
                'bookIDCategory' => 'required|exists:categorias,id_categoria',
                'bookSynopsis' => 'nullable|string',
                'bookMaxLoanDays' => 'required|integer|min:1',
                'bookCover' => 'nullable|image|max:2048', // Asegúrate de que sea una imagen y no exceda los 2MB
            ]);
        } catch (ValidationException $e) {
            $messages = implode(', ', $e->validator->errors()->all());
            $this->showValidationAlert($messages);
            return;
        } catch (Exception $e) {
            $this->showValidationAlert("Error inesperado: " . $e->getMessage());

            return;
        }
        /* Se valida si se cargó una imagen */
        // Determinar qué guardar como portada
        if ($this->bookCover) {
            // Usuario subió un archivo → se guarda en storage
            $portada = $this->bookCover->store('portadas', 'public');
        } elseif ($this->bookCoverPreview) {
            // No subió archivo, pero hay URL sugerida → se guarda la URL
            $portada = $this->guardarImagenDesdeURL($this->bookCoverPreview);
        } else {
            // No hay nada → se guarda null
            $portada = null;
        }

        $data = [
            'titulo' => $this->bookTitle,
            'isbn' => $this->bookISBN,
            'id_autor' => $this->bookIDAuthor,
            'id_editorial' => $this->bookIDPublisher,
            'anio_publicacion' => $this->bookYearPublication,
            'id_categoria' => $this->bookIDCategory,
            'sinopsis' => $this->bookSynopsis,
            // Si no se sube imagen, se asigna placeholder.png
            'portada' => $portada,
            'dias_maximos_prestamo' => $this->bookMaxLoanDays,
            'created_by' => Auth::user() ? Auth::user()->name . ' ' . Auth::user()->lastname : '',
        ];

        try {
            Libro::create($data);
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) { // Código de error para violación de restricción única
                $this->showValidationAlert('El ISBN ingresado ya existe en el sistema.');
            } else {
                $this->showValidationAlert('Error al guardar el libro: ' . $e->getMessage());
            }
            return;
        }

        $this->showSuccessAlert("create", $data['titulo'], $data['isbn'], '');
        $this->closeModal();
        $this->dispatch('refreshBookTable');
    }
    private function guardarImagenDesdeURL($url)
    {
        try {
            $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
            $nombreArchivo = 'portadas/' . Str::uuid() . '.' . $extension;

            $contenido = Http::get($url)->body();

            Storage::disk('public')->put($nombreArchivo, $contenido);

            return $nombreArchivo;
        } catch (\Exception $e) {
            return null;
        }
    }


    public function updateBook()
    {
        // Si bookCover es un string (ruta anterior), lo ponemos a null para evitar error de validación
        if (is_string($this->bookCover)) {
            $this->bookCover = null;
        }
        try {
            $this->validate([
                'bookTitle' => 'required|string|max:255',
                // Cambiado: la regla unique ignora el libro actual
                'bookISBN' => 'required|string|size:13|unique:libros,isbn,' . $this->bookID . ',id_libro',
                'bookIDAuthor' => 'required|exists:autores,id_autor',
                'bookIDPublisher' => 'required|exists:editoriales,id_editorial',
                'bookYearPublication' => 'nullable|digits:4',
                'bookIDCategory' => 'required|exists:categorias,id_categoria',
                'bookSynopsis' => 'nullable|string',
                'bookMaxLoanDays' => 'required|integer|min:1',
                'bookCover' => 'nullable|image|max:2048',
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
        $book = Libro::findOrFail($this->bookID);
        if ($this->bookCover) {
            $validated['portada'] = $this->bookCover->store('portadas', 'public');
        }

        // Mapear las variables a las columnas de la tabla
        $data = [
            'titulo' => $this->bookTitle,
            'isbn' => $this->bookISBN,
            'id_autor' => $this->bookIDAuthor,
            'id_editorial' => $this->bookIDPublisher,
            'anio_publicacion' => $this->bookYearPublication,
            'id_categoria' => $this->bookIDCategory,
            'sinopsis' => $this->bookSynopsis,
            // Si no se sube nueva imagen, se mantiene la anterior
            'portada' => $validated['portada'] ?? $book->portada,
            'dias_maximos_prestamo' => $this->bookMaxLoanDays,
            'created_by' => Auth::user() ? Auth::user()->name . ' ' . Auth::user()->lastname : '',
        ];

        $book->update($data);

        $this->showSuccessAlert("update", $data['titulo'], $data['isbn'], '');

        $this->closeModal(); // Cierra el modal
        $this->dispatch('refreshBookTable');
    }

    public function toggleAuthorInput()
    {
        $this->showAuthorSelect = !$this->showAuthorSelect;
    }
    public function togglePublisherInput()
    {
        $this->showPublisherSelect = !$this->showPublisherSelect;
    }

    public function toggleCategoryInput()
    {
        $this->showCategorySelect = !$this->showCategorySelect;
    }

    public function searchDataFromAPI()
    {
        // Oculta los selectores para que el formulario no mezcle datos viejos
        $this->showAuthorSelect = false;
        $this->showPublisherSelect = false;
        $this->showCategorySelect = false;

        // Limpia el ISBN: deja solo números y la X si viene
        $isbn = preg_replace('/[^0-9Xx]/', '', $this->bookISBN);

        // URL de la API de OpenLibrary
        $url = "https://openlibrary.org/api/books?bibkeys=ISBN:$isbn&format=json&jscmd=data";

        // Realiza la petición HTTP a OpenLibrary
        $response = Http::get($url);

        if ($response->successful()) {
            $data = $response->json();

            // Intenta obtener la información del libro por ISBN
            $info = $data["ISBN:$isbn"] ?? null;

            if ($info) {
                // Asigna título, año, autor y editorial desde los datos de OpenLibrary
                $this->bookTitle = $info['title'] ?? '';
                $this->bookYearPublication = isset($info['publish_date']) ? substr($info['publish_date'], -4) : null;
                $this->bookAuthorName = $info['authors'][0]['name'] ?? '';
                $this->bookPublisherName = $info['publishers'][0]['name'] ?? '';

                // Normaliza autor y editorial (mayúscula solo en la primera letra de cada palabra)
                $this->bookAuthorName = ucwords(strtolower(trim($this->bookAuthorName)));
                $this->bookPublisherName = ucwords(strtolower(trim($this->bookPublisherName)));

                // Intenta obtener la portada desde OpenLibrary
                $this->bookCoverPreview = $info['cover']['medium'] ?? null;
                $this->showSuccessAlert("Datos encontrados", "Se encontraron los datos del libro " . $isbn);

                // Si OpenLibrary no tiene portada, busca con API externa (bookcover-api)
                if (!$this->bookCoverPreview) {
                    $this->bookCoverPreview = $this->buscarPortadaExterna();
                }

                // Actualiza sugerencias para autocompletar
                $this->actualizarSugerencias();
            } else {
                // Si no se encontró el ISBN, aún así intenta buscar la portada con la API externa                
                $this->bookCoverPreview = $this->buscarPortadaExterna();

                // Muestra mensaje indicando que no se encontró en OpenLibrary
                session()->flash('message', 'ISBN no encontrado en Open Library. Solo se intentó recuperar la portada.');
                $this->showInfoAlert('ISBN no encontrado', 'ISBN no encontrado en Open Library. Solo se intentó recuperar la portada');
            }
        } else {
            // Error en la conexión con OpenLibrary
            session()->flash('message', 'Error al conectar con Open Library.');
        }
    }


    public function buscarPortadaExterna()
    {
        $isbn = preg_replace('/[^0-9Xx]/', '', $this->bookISBN);

        $response = Http::get("https://bookcover.longitood.com/bookcover/$isbn");
        /* dd($response); */

        if ($response->successful() && !empty($response['url'])) {
            $this->bookCoverPreview = $response['url'];
        } else {
            session()->flash('message', 'No se encontró la portada en la API externa.');
            $this->showErrorAlert('No info', 'Datos del libro no encontrados', 'No se encontró la portada en la API externa.');
        }
        return $response['url'] ?? null;
    }

    public function actualizarSugerencias()
    {
        if ($this->bookAuthorName) {
            $this->autoresSugeridos = Autor::where(function ($query) {
                $query->where('nombre', 'like', '%' . $this->bookAuthorName . '%')
                    ->orWhere('apellido_paterno', 'like', '%' . $this->bookAuthorName . '%')
                    ->orWhere('apellido_materno', 'like', '%' . $this->bookAuthorName . '%');
            })->get();
        }

        if ($this->bookPublisherName) {
            $this->editorialesSugeridas = Editorial::where('nombre_editorial', 'like', '%' . $this->bookPublisherName . '%')->get();
        }
    }

    public function updatedBookAuthorName()
    {
        $this->actualizarSugerencias();
    }

    public function updatedBookPublisherName()
    {
        $this->actualizarSugerencias();
    }

    public function handleDeleteEvent($id)
    {
        $this->delete($id);
    }
    public function delete($id)
    {
        try {
            $book = Libro::findOrFail($id); // Busca el usuario por ID
            $loggedUser = Auth::user();
            $loggedUserName = $loggedUser ? $loggedUser->name : null;
            $loggedUserLastname = $loggedUser ? $loggedUser->lastname : null;
            $book->deleted_by = $loggedUserName . ' ' . $loggedUserLastname; // Registra quién eliminó al usuario
            /* El usuario pasa estar inactivo cuando se elimina */
            $book->save(); // Guarda el valor de deleted_by en la base de datos
            $book->delete(); // Elimina el libro de la base de datos

            $this->dispatch(
                'delete-success',
                model: "book",
                id: $book->bookID,
                title: "Libro eliminados",
                text: "ID: {$book->bookID}\n
                Título: {$book->titulo}\n
                ISBN: {$book->isbn}"
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
                $this->book = null; // Reinicia los datos del usuario
                // Inicializa los selects como vacíos para que muestren el placeholder
                $this->bookIDAuthor = '';
                $this->bookIDPublisher = '';
                $this->bookIDCategory = '';

                break;
            case 'edit':
                $this->book = Libro::findOrFail($id); // Carga el usuario por ID
                $this->loadBook($this->book); // Carga los datos del usuario seleccionado
                break;
            case 'delete':
                $this->book = Libro::findOrFail($id); // Carga el usuario por ID
                $this->loadBook($this->book); // Carga los datos del usuario seleccionado
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

        $this->bookID = $bookData->id_libro;
        $this->bookTitle = $bookData->titulo;
        $this->bookISBN = $bookData->isbn;
        $this->bookIDAuthor = $bookData->id_autor;
        $this->bookIDPublisher = $bookData->id_editorial;
        $this->bookYearPublication = $bookData->anio_publicacion;
        $this->bookIDCategory = $bookData->id_categoria;
        $this->bookSynopsis = $bookData->sinopsis;
        $this->bookCover = $bookData->portada;
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

    public function showErrorAlert($errors, $title, $text)
    {
        $this->dispatch('error-alert', errors: $errors, title: $title, text: $text);
    }

    public function showInfoAlert($title, $text)
    {
        $this->dispatch('info-alert', title: $title, text: $text);
    }
}
