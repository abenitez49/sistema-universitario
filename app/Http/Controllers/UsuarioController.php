<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Persona;
use App\Models\Alumno;
use App\Models\Profesor;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::with(['persona', 'roles'])->latest()->paginate(10);
        $roles = Role::all();
        
        // Obtenemos Alumnos y Profesores cuyas 'Personas' AÚN NO tengan un usuario creado
        $alumnos = Alumno::whereHas('persona', function($q) {
            $q->doesntHave('user');
        })->with('persona')->get();

        $profesores = Profesor::whereHas('persona', function($q) {
            $q->doesntHave('user');
        })->with('persona')->get();
        
        return view('usuarios.index', compact('usuarios', 'roles', 'alumnos', 'profesores'));
    }

    public function store(Request $request)
    {
        // 1. Validaciones base compartidas
        $request->validate([
            'rol' => 'required|exists:roles,name',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        DB::transaction(function () use ($request) {
            $personaId = null;

            // 2. Lógica separada: Si es Alumno/Profesor usar ID, si no, crear Persona
            if (in_array($request->rol, ['Alumno', 'Profesor'])) {
                $request->validate([
                    'persona_id' => 'required|exists:personas,id'
                ]);
                $personaId = $request->persona_id;
            } else {
                $request->validate([
                    'documento' => 'required|string|unique:personas,documento',
                    'nombres' => 'required|string|max:255',
                    'apellidos' => 'required|string|max:255',
                    'fecha_nacimiento' => 'required|date',
                ]);

                $persona = Persona::create($request->only(['documento', 'nombres', 'apellidos', 'fecha_nacimiento']));
                $personaId = $persona->id;
            }

            // 3. Crear el usuario enlazado a la persona correcta
            $user = User::create([
                'persona_id' => $personaId,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $user->assignRole($request->rol);
        });

        return redirect()->back()->with('success', 'Usuario creado correctamente.');
    }

    public function update(Request $request, User $usuario)
    {
        // Al editar, simplemente permitimos corregir los datos personales sin importar el rol
        $request->validate([
            'documento' => 'required|string|unique:personas,documento,' . $usuario->persona_id,
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'fecha_nacimiento' => 'required|date',
            'email' => 'required|string|email|max:255|unique:users,email,' . $usuario->id,
            'rol' => 'required|exists:roles,name',
            'password' => 'nullable|string|min:8'
        ]);

        DB::transaction(function () use ($request, $usuario) {
            $usuario->persona->update($request->only(['documento', 'nombres', 'apellidos', 'fecha_nacimiento']));
            
            $userData = ['email' => $request->email];
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            $usuario->update($userData);

            $usuario->syncRoles([$request->rol]); 
        });

        return redirect()->back()->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $usuario)
    {
        // Eliminar el usuario. 
        // Importante: No borramos la persona si es Alumno o Profesor, solo borramos la cuenta de User.
        $usuario->delete();
        return redirect()->back()->with('success', 'Cuenta de usuario eliminada. Los registros como Alumno/Profesor se mantienen intactos.');
    }
}