<?php

namespace App\Http\Controllers;

use App\Models\BrandProduct;
use App\Models\Categories;
use App\Models\Product;
use App\Models\TypeProduct;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $product = Product::with(['category', 'typeProduct', 'brandProduct'])->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Products retrieved successfully',
            'data' => $product,
        ], 201);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Categories::all();
        $types = TypeProduct::all();
        $brands = BrandProduct::all();

        return response()->json([
            'status' => 'success',
            'message' => 'Data for creation retrieved successfully',
            'data' => [
                'categories' => $categories,
                'types' => $types,
                'brands' => $brands,
            ],
        ], 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'type_id' => 'required|exists:type_product,id',
            'brand_id' => 'required|exists:brand_product,id',
            'product_name' => 'required|string|max:150',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'rating' => 'nullable|numeric|min:0|max:5',
            'spesification_product' => 'nullable|string|max:1500',
            'information_product' => 'nullable|string|max:1500',
        ]);

        Product::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Product created successfully',
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
        $product = Product::findOrFail($id);

        $categories = Categories::all();
        $types = TypeProduct::all();
        $brands = BrandProduct::all();

        return response()->json([
            'status' => 'success',
            'message' => 'Product and options retrieved successfully',
            'data' => [
                'product' => $product,
                'categories' => $categories,
                'types' => $types,
                'brands' => $brands,
            ],
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'type_id' => 'required|exists:type_product,id',
            'brand_id' => 'required|exists:brand_product,id',
            'product_name' => 'required|string|max:150',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'rating' => 'nullable|numeric|min:0|max:5',
            'spesification_product' => 'nullable|string|max:1500',
            'information_product' => 'nullable|string|max:1500',
        ]);

        $product = Product::findOrFail($id);
        $product->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Product updated successfully',
            'data' => $validated,
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Product deleted successfully',
        ], 201);
    }
}