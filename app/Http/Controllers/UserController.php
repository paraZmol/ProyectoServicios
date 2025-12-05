<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserStoreRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $users = User::query()
            ->where('id', '!=', Auth::id())

            ->when($search, function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%')
                      ->orWhere('email', 'like', '%' . $search . '%');
            })

            ->orderBy('name', 'asc')
            ->paginate(10);

        return view('users.index', compact('users', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request)
    {
        $validated = $request->validated();

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserStoreRequest $request, User $user)
    {
        $validated = $request->validated();

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if (Auth::id() == $user->id) {
            return redirect()->route('users.index')->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuario eliminado correctamente.');
    }

    public function deleted()
    {
        // solo eliminados
        $deletedUsers = User::onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->paginate(10);

        return view('users.deleted_index', [
            'users' => $deletedUsers,
        ]);
    }

    // restaurar eliminados
    public function restore($id)
    {
        // encontrar por id
        $user = User::onlyTrashed()->findOrFail($id);

        // restaurar
        $user->restore();

        return redirect()->route('users.deleted')->with('success', '✅ Usuario "' . $user->name . '" restaurado con éxito. Su cuenta está de nuevo activa.');
    }

    // eliminacion permante usuario
    public function forceDelete($id)
    {
        //Log::info("🔴 [ForceDelete] Iniciando proceso para Usuario ID: " . $id);

        $user = User::withTrashed()->find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'Usuario no encontrado.');
        }

        if (Auth::id() == $user->id) {
            return redirect()->back()->with('error', 'No puedes eliminar tu propia cuenta permanentemente.');
        }

        DB::beginTransaction();

        try {
            //buscar o crear al desvinculado
            $dummyUser = User::withTrashed()
                            ->where('email', 'desvinculado@sistema.com')
                            ->first();

            if (!$dummyUser) {
                $dummyUser = User::create([
                    'name'     => 'USUARIO DESVINCULADO',
                    'email'    => 'desvinculado@sistema.com',
                    'password' => Hash::make('password_imposible_' . rand(1000,9999)),
                    'role'     => 'usuario'
                ]);
            }

            if ($dummyUser->trashed()) {
                $dummyUser->restore();
            }

            // resgianar  alas boletas
            $affectedInvoices = 0;

            // verificacion de xexistencias
            if (Schema::hasColumn('invoices', 'user_id')) {
                $affectedInvoices = DB::table('invoices')
                    ->where('user_id', $user->id)
                    ->update(['user_id' => $dummyUser->id]);

                //Log::info("🔴 [ForceDelete] Boletas reasignadas: " . $affectedInvoices);
            }

            // eliminacion otal
            $user->forceDelete();

            DB::commit();

            return redirect()->route('users.deleted')
                ->with('success', "Usuario eliminado permanentemente. Se reasignaron {$affectedInvoices} boletas a 'USUARIO DESVINCULADO'.");

        } catch (\Exception $e) {
            DB::rollBack();
            //Log::error("🔴 [ForceDelete] ERROR: " . $e->getMessage());
            return redirect()->back()->with('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }

}
