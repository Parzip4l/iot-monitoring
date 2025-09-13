<?php

namespace App\Http\Controllers\Log;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\General\MqttLog;
use App\Models\General\Device;


class LogController extends Controller
{
    /**
     * List roles
     */
    public function index(Request $request)
    {
        $query = MqttLog::query()->with('device');

        // Filter device
        if ($request->device_id) {
            $query->where('device_id', $request->device_id);
        }

        // Filter tanggal
        if ($request->from && $request->to) {
            $query->whereBetween('last_saved_at', [$request->from . ' 00:00:00', $request->to . ' 23:59:59']);
        }

        $log = $query->orderBy('last_saved_at', 'desc')->get();
        $devices = Device::all(); // Ambil daftar device untuk dropdown

        return view('pages.log.index', compact('log', 'devices'));
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
