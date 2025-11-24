<?php

namespace App\Http\Controllers;

use App\Models\TypeProduct;
use Illuminate\Http\Request;

class TypeProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $typeProduct = TypeProduct::all();
        return response()->json([
            'status' => 'success',
            'message' => 'Type products retrieved successfully',
            'data' => $typeProduct,
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
            'type_name' => 'required|string|max:150',
            'description' => 'nullable|string|max:255',
        ]);

        TypeProduct::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Type product created successfully',
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
        $types = TypeProduct::findOrFail($id);

        return response()->json([
            'status' => 'Ok',
            'message' => 'Get data type by id successfully!',
            'datas' => $types,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'type_name' => 'required|string|max:150',
            'description' => 'nullable|string|max:255',
        ]);

        $typeProduct = TypeProduct::findOrFail($id);
        $typeProduct->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Type product updated successfully',
            'data' => $validated,
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $typeProduct = TypeProduct::findOrFail($id);
        $typeProduct->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Type product deleted successfully',
        ], 201);
    }
}