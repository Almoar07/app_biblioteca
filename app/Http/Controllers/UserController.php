<?php

namespace App\Http\Controllers;

use App\Livewire\LiveModalEditUser;
use Illuminate\Http\Request;
use App\Models\User; // Importa el modelo User
use Illuminate\Support\Facades\Log; // Importa el facade Log
use Illuminate\Support\Facades\Password; // Importa el facade Password
use Illuminate\Support\Facades\Mail; // Importa el facade Mail
use Illuminate\Validation\ValidationException; // Importa ValidationException
use Illuminate\Support\Facades\Validator; // Importa Validator
use PHPUnit\Event\Emitter;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function index()
    {

        $usuarios = User::all(); // Obtiene todos los usuarios
        /* $usuarios = User::paginate(10); // Pagina los usuarios, 10 por página */
        return view('administracion.usuarios', compact('usuarios'));
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $users = \App\Models\User::all();
        return response()->json($users);
    }



    /**
     * Update the specified resource in storage.
     */
    public function updateUser(array $data, string $id)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'lastname2' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'phone' => 'required|string|max:15',
            'tipo_usuario' => 'required|string|in:admin,bibliotecario,invitado',
            'status' => 'required|string|in:activo,inactivo,bloqueado',
            'birthday' => 'required|date'
        ]);

        if ($validator->fails()) {

            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::findOrFail($id);
        $user->update($validator->validated());
        event('successEditingUser', [$user]);
    }





    public function deleteUser(string $id)
    {
        //
        // Buscar el usuario por ID
        $user = User::findOrFail($id);
        // Eliminar el usuario
        $user->delete();
        // Redirigir con un mensaje de éxito
        return redirect()->route('administracion.usuarios')->with('success', 'Usuario eliminado correctamente.');
    }
}
