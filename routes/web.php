<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EtiquetaBarCodeController;
use App\Http\Controllers\EjemplaresController;
use App\Http\Controllers\EstudiantesController;
use App\Http\Controllers\AutoresController;
use App\Http\Controllers\CategoriasController;
use App\Http\Controllers\EditorialesController;
use App\Http\Controllers\LibrosController;
use App\Http\Controllers\PrestamosController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\UserController;


//Controladores de autenticación
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;

/* Importaciones para reportes */
use App\Http\Controllers\ReportesController;
use App\Exports\PrestamosPorFechaExport;
use App\Exports\PrestamosPorLector;
use App\Exports\StockPorLibroExport;
use App\Livewire\LiveLoanTable;
use App\Models\Prestamo;
use Maatwebsite\Excel\Facades\Excel;

Route::view('/', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

/* Rutas de contraseña */
Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
    ->middleware('guest')
    ->name('password.request');

Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.update');

/* RUTAS ADMINISTRADORES */
/* Route::get('/administracion', function () {
    return view('administracion');
})->middleware(['auth', 'verified', 'checkUserType:admin', 'checkUserStatus'])->name('administracion'); */

Route::middleware(['auth', 'verified', 'checkUserType:admin', 'checkUserStatus:activo'])->group(function () {
    Route::get('/administracion', function () {
        return view('administracion');
    })->name('administracion');

    /* Ruta hacia la gestión de bibliotecarios*/
    Route::get('/administracion/usuarios', [UserController::class, 'index'])
        ->name('administracion.usuarios');
    // Aquí irían otras rutas protegidas para admin
});


Route::middleware(['auth', 'verified', 'checkUserStatus:activo'])->group(function () {
    /* Route::get('/administracion', function () {
        return view('administracion');
    })->name('administracion');
 */
    /* Ruta hacia la gestión de bibliotecarios*/
    /* Route::get('/administracion/usuarios', [UserController::class, 'index'])
        ->name('administracion.usuarios'); */
    // Aquí irían otras rutas protegidas para admin

    /******************** RUTAS AUTORES ********************/
    Route::get('/administracion/autores', [AutoresController::class, 'index'])
        ->middleware(['auth', 'verified'])
        ->name('administracion.autores');

    /******************** RUTAS ESTUDIANTES ********************/
    Route::get('/administracion/estudiantes', [EstudiantesController::class, 'index'])
        ->middleware(['auth', 'verified'])
        ->name('administracion.estudiantes');

    /******************** RUTAS CATEGORIAS ********************/
    Route::get('/administracion/categorias', [CategoriasController::class, 'index'])
        ->middleware(['auth', 'verified'])
        ->name('administracion.categorias');

    /******************** RUTAS EDITORIALES ********************/
    Route::get('/administracion/editoriales', [EditorialesController::class, 'index'])
        ->middleware(['auth', 'verified'])
        ->name('administracion.editoriales');

    /******************** RUTAS LIBROS ********************/
    Route::get('/administracion/libros', [LibrosController::class, 'index'])
        ->middleware(['auth', 'verified'])
        ->name('administracion.libros');

    /******************** RUTAS EJEMPLARES ********************/
    Route::get('/administracion/ejemplares', [EjemplaresController::class, 'index'])
        ->middleware(['auth', 'verified'])
        ->name('administracion.ejemplares');

    /******************** RUTAS PRESTAMOS ********************/
    Route::get('/administracion/prestamos', [PrestamosController::class, 'index'])
        ->middleware(['auth', 'verified'])
        ->name('administracion.prestamos');

    Route::get('/administracion/prestamos/{id}', [PrestamosController::class, 'show'])
        ->middleware(['auth', 'verified'])
        ->name('administracion.prestamos.show');

    /* RUTA PARA GENERAR ETIQUETAS DE CODIGO DE BARRA */
    Route::get('/etiquetas/pdf', [EtiquetaBarCodeController::class, 'generarPDF'])->name('etiquetas.pdf');

    /* Ruta vista de reportes */
    Route::get('/administracion/reportes', [ReportesController::class, 'index'])->name('reportes.index');


    //StockPorLibros
    Route::get('/reportes/stock', function () {
        return Excel::download(new StockPorLibroExport, 'stock_de_libros.xlsx');
    })->middleware(['auth', 'verified']);

    //Prestamos por fecha
    Route::get('/reportes/prestamos-por-fechas', [ReportesController::class, 'prestamosPorFecha'])->middleware(['auth', 'verified'])
        ->name('reportes.prestamos-por-fechas');

    //Libros más prestados por fecha.
    Route::get('/reportes/mas-prestados', [ReportesController::class, 'librosMasPrestados'])->middleware(['auth', 'verified'])
        ->name('reportes.mas-prestados');

    //Préstamos por lector    
    Route::get('/reportes/prestamos-por-lector', [ReportesController::class, 'prestamosPorLector'])->middleware(['auth', 'verified'])
        ->name('reportes.prestamos-por-lector');
});






/* RUTAS REPORTES */
/******************** RUTA VISTA REPORTES ********************/






require __DIR__ . '/auth.php';
