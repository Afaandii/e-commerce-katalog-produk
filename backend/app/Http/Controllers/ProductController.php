<?php

namespace App\Http\Controllers;

use App\Models\BrandProduct;
use App\Models\Categories;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\TypeProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

    public function getDataShop(string $nama, int $id)
    {
        try {
            // Ambil produk berdasarkan ID
            $product = Product::with(['category', 'typeProduct', 'brandProduct'])
                ->find($id);

            // Jika produk tidak ditemukan
            if (!$product) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Produk tidak ditemukan.',
                ], 404);
            }

            // Validasi nama produk untuk SEO friendly URL
            // Slugify nama produk untuk membandingkan
            $slugifiedProductName = strtolower(trim(preg_replace('/[^a-zA-Z0-9\s]/', '', $product->product_name)));
            $slugifiedNama = strtolower(trim(preg_replace('/[^a-zA-Z0-9\s]/', '', $nama)));

            // Ganti spasi dengan `-`
            $slugifiedProductName = str_replace(' ', '-', $slugifiedProductName);
            $slugifiedNama = str_replace(' ', '-', $slugifiedNama);

            // Hapus karakter `-` di awal/akhir
            $slugifiedProductName = trim($slugifiedProductName, '-');
            $slugifiedNama = trim($slugifiedNama, '-');

            // Jika nama tidak cocok, redirect ke URL yang benar (opsional)
            // Atau biarkan saja jika ingin tetap menampilkan produk meski nama tidak match
            if ($slugifiedNama !== $slugifiedProductName) {
                // Opsional: bisa redirect atau hanya log
                // Untuk sekarang, kita biarkan tampil tapi kasih warning di log
                Log::warning("URL slug mismatch: requested '{$nama}' but product name is '{$product->product_name}'");
            }

            // Ambil semua gambar produk
            $images = ProductImage::where('product_id', $product->id)->get();

            // Format respons
            $responseData = [
                'status' => 'success',
                'message' => 'Detail produk berhasil diambil.',
                'data' => [
                    'id' => $product->id,
                    'title' => $product->product_name,
                    'price' => $product->price,
                    'rating' => $product->rating,
                    'sold' => $product->sold ?? "0",
                    'stock' => $product->stock,
                    'images' => $images->map(function ($img) {
                        return [
                            'url' => $img->image_url,
                            'alt' => $img->product->product_name . " - Gambar {$img->id}",
                        ];
                    })->toArray(),
                    'condition' => $product->condition ?? "Baru",
                    'minOrder' => $product->min_order ?? 1,
                    'category' => $product->category ? $product->category->category_name : "Kategori Tidak Diketahui",
                    'description' => $product->information_product,
                    'features' => explode("\n", $product->spesification_product),
                ],
            ];

            return response()->json($responseData, 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengambil data produk.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
