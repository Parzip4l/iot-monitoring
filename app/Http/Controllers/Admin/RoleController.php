<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Auth\Role;

class RoleController extends Controller
{
    /**
     * List roles
     */
    public function index()
    {
        $roles = Role::all();
        return view('pages.admin.role.index', compact('roles'));
    }

    /**
     * Form create
     */
    public function create()
    {
        return view('pages.admin.role.create');
    }

    /**
     * Store new role
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
        ]);

        try {
            Role::create([
                'name' => strtolower($request->name),
            ]);

            return redirect()
                ->route('role.index')
                ->with('success', 'Role berhasil ditambahkan');
        } catch (\Exception $e) {
            // Bisa juga ditambahkan log error
            \Log::error('Error saat menambahkan role: '.$e->getMessage());

            return redirect()
                ->route('role.index')
                ->with('error', 'Terjadi kesalahan saat menambahkan role.');
        }
    }

    /**
     * Form edit role
     */
    public function edit(Role $role)
    {
        return view('pages.admin.role.edit', compact('role'));
    }

    /**
     * Update role
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
        ]);

        try {
            $role->update([
                'name' => strtolower($request->name),
            ]);

            return redirect()
                ->route('role.index')
                ->with('success', 'Role berhasil diperbarui');
        } catch (\Exception $e) {
            \Log::error('Error saat update role ID '.$role->id.': '.$e->getMessage());

            return redirect()
                ->route('role.index')
                ->with('error', 'Terjadi kesalahan saat memperbarui role.');
        }
    }

    /**
     * Hapus role
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('role.index')->with('success', 'Role berhasil dihapus');
    }
}
