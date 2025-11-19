<?php

namespace App\Http\Controllers;

use App\Models\BrandProduct;
use Illuminate\Http\Request;

class BrandProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brandProducts = BrandProduct::all();
        return response()->json([
            'status' => 'success',
            'message' => 'Brand products retrieved successfully',
            'data' => $brandProducts,
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
            'brand_name' => 'required|string|max:150',
            'description' => 'nullable|string|max:255',
        ]);

        BrandProduct::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Brand product created successfully',
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
            'brand_name' => 'required|string|max:150',
            'description' => 'nullable|string|max:255',
        ]);

        $brandProduct = BrandProduct::findOrFail($id);
        $brandProduct->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Brand product updated successfully',
            'data' => $validated,
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $brandProduct = BrandProduct::findOrFail($id);
        $brandProduct->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Brand product deleted successfully',
        ], 201);
    }
}
