<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Services\SupabaseStorage;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProductImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $images = ProductImage::with('product')->get();
        return response()->json([
            'success' => 'Ok',
            'message' => 'Product image retrieved successfully',
            'data' => $images
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $product = Product::all();

        return response()->json([
            'status' => 'success',
            'message' => 'Data for creation product image retrieved successfully',
            'data' => [
                'product' => $product,
            ],
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:product,id',
            'image' => 'required|image|max:2048'
        ]);

        $file = $request->file('image');
        $fileName = 'product_' . $request->product_id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $filePath = "products/{$fileName}";

        $supabase = app(SupabaseStorage::class)->setBucket(env('SUPABASE_BUCKET_PRODUCTS'));
        $supabase->upload($filePath, $file);

        $url = $supabase->publicUrl($filePath);

        $img = ProductImage::create([
            'product_id' => $request->product_id,
            'image_url' => $url
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product image created successfully!',
            'data' => $img
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
        $productImage = ProductImage::findOrFail($id);
        $products = Product::all();

        return response()->json([
            'status' => 'success',
            'message' => 'Product image retrieved successfully',
            'data' => [
                'product_image' => $productImage,
                'products' => $products,
            ],
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $image = ProductImage::find($id);
        if (!$image) {
            return response()->json([
                'success' => false,
                'message' => 'Image not found',
            ], 404);
        }

        $request->validate([
            'product_id' => 'nullable|exists:product,id',
            'image'      => 'nullable|image|max:2048'
        ]);

        $supabase = app(SupabaseStorage::class);

        $updateData = [];
        if ($request->filled('product_id')) {
            $updateData['product_id'] = (int) $request->product_id;
        }

        if ($request->hasFile('image')) {
            $oldPath = preg_replace(
                "#^" . preg_quote(env('SUPABASE_URL') . '/storage/v1/object/public/' . env('SUPABASE_BUCKET') . '/', '#') . "#",
                '',
                $image->image_url
            );

            try {
                $supabase->delete($oldPath);
            } catch (\Exception $e) {
                Log::warning("Gagal menghapus file di Supabase: " . $e->getMessage() . " | Path lama: " . $oldPath);
            }

            $file = $request->file('image');
            $fileName = 'product_' . ($updateData['product_id'] ?? $image->product_id) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = "products/{$fileName}";

            $supabase->upload($filePath, $file);

            $updateData['image_url'] = $supabase->publicUrl($filePath);
        }
        $image->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Product image updated successfully',
            'data' => $image
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $image = ProductImage::find($id);

        if (!$image) {
            return response()->json([
                'success' => false,
                'message' => 'Image not found',
            ], 404);
        }

        $supabase = app(SupabaseStorage::class);

        $path = str_replace(env('SUPABASE_URL') . '/storage/v1/object/public/' . env('SUPABASE_BUCKET') . '/', '', $image->image_url);

        try {
            $supabase->delete($path);
        } catch (\Exception $e) {
            // ignore error supaya tetap bisa hapus DB
            Log::warning("Gagal menghapus file di Supabase: " . $e->getMessage() . " | Path: " . $path);
        }

        $image->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product image deleted successfully'
        ], 200);
    }
}
