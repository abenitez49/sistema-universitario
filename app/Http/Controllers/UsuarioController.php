<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Persona;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
    public function index()
    {
        // Traemos a los usuarios con su persona y roles para evitar problemas de N+1 queries
        $usuarios = User::with(['persona', 'roles'])->latest()->paginate(10);
        $roles = Role::all();
        
        return view('usuarios.index', compact('usuarios', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'documento' => 'required|string|unique:personas,documento',
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'fecha_nacimiento' => 'required|date',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'rol' => 'required|exists:roles,name'
        ]);

        // La transacción asegura que ambas tablas se guarden juntas o fallen juntas
        DB::transaction(function () use ($request) {
            $persona = Persona::create($request->only(['documento', 'nombres', 'apellidos', 'fecha_nacimiento']));
            
            $user = User::create([
                'persona_id' => $persona->id,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $user->assignRole($request->rol);
        });

        return redirect()->back()->with('success', 'Usuario creado correctamente.');
    }

    public function update(Request $request, User $usuario)
    {
        $request->validate([
            'documento' => 'required|string|unique:personas,documento,' . $usuario->persona_id,
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'fecha_nacimiento' => 'required|date',
            'email' => 'required|string|email|max:255|unique:users,email,' . $usuario->id,
            'rol' => 'required|exists:roles,name',
            'password' => 'nullable|string|min:8' // Es opcional al editar
        ]);

        DB::transaction(function () use ($request, $usuario) {
            $usuario->persona->update($request->only(['documento', 'nombres', 'apellidos', 'fecha_nacimiento']));
            
            $userData = ['email' => $request->email];
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            $usuario->update($userData);

            $usuario->syncRoles([$request->rol]); // Reemplaza el rol anterior por el nuevo
        });

        return redirect()->back()->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $usuario)
    {
        // Como la tabla 'users' depende de 'personas', borramos la persona base 
        // y el borrado en cascada (cascadeOnDelete) que configuraste en tu migración se encargará del usuario.
        $usuario->persona->delete();
        
        return redirect()->back()->with('success', 'Usuario eliminado correctamente.');
    }
}