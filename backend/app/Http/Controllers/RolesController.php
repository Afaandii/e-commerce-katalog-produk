<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Roles::all();
        return response()->json([
            'status' => 'success',
            'message' => 'Roles retrieved successfully',
            'data' => $roles,
        ], 201);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'role_name' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
        ]);

        Roles::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Role created successfully',
            'data' => $validated,
        ], 201);
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'role_name' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
        ]);

        $role = Roles::findOrFail($id);
        $role->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Role updated successfully',
            'data' => $validated,
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Roles::findOrFail($id);
        $role->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Role deleted successfully',
        ], 201);
    }
}
