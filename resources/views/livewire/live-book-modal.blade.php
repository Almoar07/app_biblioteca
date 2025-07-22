<div x-data="{ open: @entangle('showModal') }" x-cloak>
    @php
        switch ($mode) {
            case 'create':
                $tituloModal = 'Crear libro';
                $subtituloModal = 'Ingrese los datos del nuevo libro';
                $buttonAction = 'createBook()';
                break;
            case 'edit':
                $tituloModal = 'Editar libro';
                $subtituloModal = 'Complete o modifique la información del libro';
                $buttonAction = 'updateBook()';
                break;
            default:
                $tituloModal = 'Sin título';
                $subtituloModal = 'No hay un modo definido';
                $buttonAction = '';
                break;
        }
    @endphp

    <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-800 bg-opacity-75 transition-opacity" aria-hidden="true">
    </div>

    <div x-show="open" class="fixed inset-0 z-10 w-screen overflow-y-auto ">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div x-show="open" x-transition:enter="ease-out duration-300">
                <div class="bg-gray-100 dark:bg-gray-900 px-4 pt-5 pb-4 sm:p-6 sm:pb-4 rounded-t-lg">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-4 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <div class="border-b border-gray-200 dark:border-gray-700 pb-3 flex items-start">
                                <div
                                    class="mr-4 flex size-14 shrink-0 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900 sm:size-12">
                                    <svg class="size-7 text-blue-600 dark:text-blue-400" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">
                                        {{ $tituloModal }}
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $subtituloModal }}
                                    </p>
                                </div>
                            </div>
                            <form>
                                <div class="mt-5 grid grid-cols-1 sm:grid-cols-3 gap-5">
                                    <div class="space-y-5">
                                        <fieldset class="space-y-4 p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300 px-2">
                                                Información del Libro</p>

                                            <div class="flex items-center space-x-2">
                                                <div class="flex-1">
                                                    <x-input-label for="bookISBN" :value="__('ISBN')" class="sr-only" />
                                                    <input type="text" id="bookISBN" name="bookISBN"
                                                        wire:model="bookISBN" wire:keydown.enter="searchDataFromAPI"
                                                        class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block w-full"
                                                        placeholder="ISBN" />
                                                    <x-input-error class="mt-1 text-sm" :messages="$errors->get('bookISBN')" />
                                                </div>
                                                <button type="button" wire:target="searchDataFromAPI"
                                                    wire:loading.attr="disabled"
                                                    class="inline-flex items-center px-2 py-2 rounded-md bg-blue-500 text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400"
                                                    title="Buscar datos por ISBN">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
                                                    </svg>
                                                </button>

                                                <!-- Loader centrado al 50% del modal -->
                                                <div wire:loading wire:target="searchDataFromAPI"
                                                    class="absolute top-1/2 left-1/2 w-1/2 h-1/2 -translate-x-1/2 -translate-y-1/2 bg-white/70 dark:bg-black/50 z-50 hidden items-center justify-center rounded-md"
                                                    wire:loading.class.remove="hidden" wire:loading.class="flex">

                                                    <!-- Contenedor interior centrado vertical y horizontalmente -->
                                                    <div
                                                        class="h-full w-full flex flex-col items-center justify-center text-blue-600 text-center">
                                                        <svg class="animate-spin h-6 w-6 mb-2"
                                                            xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12"
                                                                r="10" stroke="currentColor" stroke-width="4" />
                                                            <path class="opacity-75" fill="currentColor"
                                                                d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 100 16v-4l-3 3 3 3v-4a8 8 0 01-8-8z" />
                                                        </svg>
                                                        <p class="text-sm">Consultando Open Library...</p>
                                                    </div>
                                                </div>


                                            </div>

                                            <div>
                                                <x-input-label for="bookTitle" :value="__('Título del libro')" class="sr-only" />
                                                <x-text-input wire:model="bookTitle" id="bookTitle" name="bookTitle"
                                                    type="text" class="block w-full" placeholder="Título del libro"
                                                    required autofocus />
                                                <x-input-error class="mt-1 text-sm" :messages="$errors->get('bookTitle')" />
                                            </div>

                                            <div class="mb-4 flex items-center space-x-2">
                                                @if ($showAuthorSelect)
                                                    <div class="flex-1">
                                                        <x-input-label for="bookIDAuthor" :value="__('Autor')"
                                                            class="sr-only" />
                                                        <select wire:model="bookIDAuthor" id="bookIDAuthor"
                                                            class="mt-2 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition duration-150">
                                                            <option value="" disabled>Seleccione un autor</option>
                                                            @foreach ($authors as $author)
                                                                <option value="{{ $author->id_autor }}">
                                                                    {{ $author->nombre }}
                                                                    {{ $author->apellido_paterno }}
                                                                    {{ $author->apellido_materno }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <x-input-error class="mt-1 text-sm" :messages="$errors->get('bookIDAuthor')" />
                                                    </div>
                                                @else
                                                    <div class="flex-1">
                                                        <x-text-input type="text"
                                                            wire:model.debounce.500ms="bookAuthorName"
                                                            list="listaAutores" id="bookAuthorName"
                                                            class="mt-2 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition duration-150"
                                                            placeholder="Autor" required autofocus />
                                                        <datalist id="listaAutores">
                                                            @foreach ($autoresSugeridos as $autor)
                                                                <option value="{{ $autor->nombre }}"></option>
                                                            @endforeach
                                                        </datalist>
                                                        <x-input-error class="mt-1 text-sm" :messages="$errors->get('bookAuthorName')" />
                                                    </div>
                                                @endif
                                                <button type="button" wire:click="toggleAuthorInput"
                                                    class="inline-flex items-center px-2 py-2 rounded-md bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-400"
                                                    title="Cambiar modo de ingreso de autor">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                                    </svg>
                                                </button>
                                            </div>

                                        </fieldset>
                                    </div>
                                    <div class="space-y-5">
                                        <fieldset class="space-y-4 p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300 px-2">
                                                Detalles Adicionales</p>
                                            <div>
                                                <x-input-label for="bookYearPublication" :value="__('Año de publicación')"
                                                    class="sr-only" />
                                                <x-text-input wire:model="bookYearPublication"
                                                    id="bookYearPublication" name="bookYearPublication"
                                                    type="number" min="1000" max="{{ date('Y') + 1 }}"
                                                    class="block w-full" placeholder="Año de publicación" />
                                                <x-input-error class="mt-1 text-sm" :messages="$errors->get('bookYearPublication')" />
                                            </div>

                                            <div class="mb-4 flex items-center space-x-2">
                                                @if ($showPublisherSelect)
                                                    <div class="flex-1">
                                                        <x-input-label for="bookIDPublisher" :value="__('Editorial')"
                                                            class="sr-only" />
                                                        <select wire:model="bookIDPublisher" id="bookIDPublisher"
                                                            class="mt-2 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition duration-150">
                                                            <option value="" disabled>Seleccione una editorial
                                                            </option>
                                                            @foreach ($publishers as $publisher)
                                                                <option value="{{ $publisher->id_editorial }}">
                                                                    {{ $publisher->nombre_editorial }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <x-input-error class="mt-1 text-sm" :messages="$errors->get('bookIDPublisher')" />
                                                    </div>
                                                @else
                                                    <div class="flex-1">
                                                        <x-text-input type="text"
                                                            wire:model.debounce.500ms="bookPublisherName"
                                                            list="listaEditoriales" id="editorial"
                                                            class="mt-2 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition duration-150"
                                                            placeholder="Editorial" />
                                                        <datalist id="listaEditoriales">
                                                            @foreach ($editorialesSugeridas as $editorial)
                                                                <option value="{{ $editorial->nombre }}">
                                                            @endforeach
                                                        </datalist>
                                                        <x-input-error class="mt-1 text-sm" :messages="$errors->get('bookPublisherName')" />
                                                    </div>
                                                @endif
                                                <button type="button" wire:click="togglePublisherInput"
                                                    class="inline-flex items-center px-2 py-2 rounded-md bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-400"
                                                    title="Cambiar modo de ingreso de editorial">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                                    </svg>
                                                </button>
                                            </div>

                                            <div class="mb-4 flex items-center space-x-2">
                                                @if ($showCategorySelect)
                                                    <div class="flex-1">
                                                        <x-input-label for="bookIDCategory" :value="__('Categoría')"
                                                            class="sr-only" />
                                                        <select wire:model="bookIDCategory" id="bookIDCategory"
                                                            class="mt-2 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition duration-150">
                                                            <option value="" disabled>Seleccione una categoría
                                                            </option>
                                                            @foreach ($categories as $category)
                                                                <option value="{{ $category->id_categoria }}">
                                                                    {{ $category->nombre_categoria }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <x-input-error class="mt-1 text-sm" :messages="$errors->get('bookIDCategory')" />
                                                    </div>
                                                @else
                                                    <div class="flex-1">
                                                        <x-text-input type="text"
                                                            wire:model.debounce.500ms="bookCategoryName"
                                                            list="listaCategorias" id="bookCategoryName"
                                                            class="mt-2 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition duration-150"
                                                            placeholder="Categoría" />
                                                        <datalist id="listaCategorias">
                                                            @foreach ($categoriasSugeridas as $categoria)
                                                                <option value="{{ $categoria->nombre }}">
                                                            @endforeach
                                                        </datalist>
                                                        <x-input-error class="mt-1 text-sm" :messages="$errors->get('bookCategoryName')" />
                                                    </div>
                                                @endif
                                                <button type="button" wire:click="toggleCategoryInput"
                                                    class="inline-flex items-center px-2 py-2 rounded-md bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-400"
                                                    title="Cambiar modo de ingreso de categoría">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <div>
                                                <x-input-label for="bookMaxLoanDays" :value="__('Días de préstamo')"
                                                    class="sr-only" />
                                                <x-text-input wire:model="bookMaxLoanDays" id="bookMaxLoanDays"
                                                    name="bookMaxLoanDays" type="number" class="block w-full"
                                                    placeholder="Días de préstamo" required autofocus />
                                                <x-input-error class="mt-1 text-sm" :messages="$errors->get('bookMaxLoanDays')" />
                                            </div>

                                        </fieldset>
                                    </div>
                                    <div class="space-y-5 sm:col-span-1 row-span-2"> {{-- Añadido row-span-2 --}}
                                        <fieldset
                                            class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm space-y-3 max-h-96 flex flex-col">
                                            {{-- Input para subir la portada --}}
                                            <div class="relative w-full mb-2">
                                                <x-input-label for="bookCover" :value="__('Portada del libro')" class="sr-only" />
                                                <input wire:model="bookCover" id="bookCover" name="bookCover"
                                                    type="file" accept="image/*"
                                                    class="block w-full text-sm text-gray-700 dark:text-gray-200 file:mr-4 file:py-2 file:px-4
                   file:rounded-lg file:border-0 file:text-sm file:font-semibold
                   file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100
                   dark:file:bg-gray-700 dark:file:text-gray-200 dark:hover:file:bg-gray-600" />
                                                <x-input-error class="mt-1 text-sm" :messages="$errors->get('bookCover')" />
                                            </div>

                                            {{-- Contenedor para la imagen de previsualización (ocupa el espacio restante) --}}
                                            <div class="flex-grow flex items-center justify-center overflow-hidden">
                                                {{-- flex-grow para ocupar el espacio --}}
                                                @if ($bookCover)
                                                    <img src="{{ $bookCover instanceof \Illuminate\Http\UploadedFile ? $bookCover->temporaryUrl() : asset('storage/' . $bookCover) }}"
                                                        alt="Portada del libro"
                                                        class="object-contain max-h-full max-w-full rounded shadow border border-gray-200 dark:border-gray-700" />
                                                @elseif ($bookCoverPreview)
                                                    <img src="{{ $bookCoverPreview }}" alt="Portada sugerida"
                                                        class="object-contain max-h-full max-w-full rounded shadow border border-blue-400 dark:border-blue-600" />
                                                @else
                                                    <p class="text-sm text-gray-500 text-center">No se encontró una
                                                        portada sugerida</p>
                                                @endif
                                            </div>
                                        </fieldset>
                                    </div>

                                    {{-- Sinopsis - Ahora ocupa 2 columnas en pantallas sm y mayores --}}
                                    <div class="sm:col-span-2"> {{-- Añadido sm:col-span-2 --}}
                                        <fieldset class="space-y-2 p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                                            <x-input-label for="bookSynopsis" :value="__('Sinopsis del libro')" />
                                            <textarea wire:model="bookSynopsis" id="bookSynopsis" name="bookSynopsis"
                                                class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition duration-150"
                                                rows="6" placeholder="Escriba aquí la sinopsis del libro"></textarea>
                                            <x-input-error class="mt-1 text-sm" :messages="$errors->get('bookSynopsis')" />
                                        </fieldset>
                                    </div>
                                </div>
                            </form>
                            <div
                                class="w-full flex flex-col sm:flex-row items-center justify-between bg-gray-50 dark:bg-gray-900 px-4 py-3 mt-6 sm:px-6 gap-2">
                                <div class="text-xs text-gray-500 dark:text-gray-400 text-left w-full sm:w-auto">
                                    Los datos de los libros se obtienen desde la API de <a
                                        href="https://openlibrary.org/developers/api" target="_blank"
                                        class="underline hover:text-blue-600">Open Library</a>.
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 text-left w-full sm:w-auto mt-1">
                                    La portada sugerida se obtiene desde <a href="https://www.goodreads.com/"
                                        target="_blank" class="underline hover:text-blue-600">Goodreads</a> utulizando
                                    la API de W3sley en
                                    Github
                                    <a href="https://github.com/w3slley/bookcover-api" target="_blank"
                                        class="underline hover:text-blue-600">API de W3slley en Github</a>.
                                </div>
                                <div class="flex flex-col sm:flex-row-reverse gap-2 w-full sm:w-auto">
                                    <button type="button" wire:click="{{ $buttonAction }}"
                                        class="inline-flex w-full sm:w-auto justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3">Guardar</button>
                                    <button @click="open = false" type="button"
                                        class="inline-flex w-full sm:w-auto justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Cancelar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
