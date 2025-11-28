<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItems;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Cari keranjang milik user yang sedang login
        $cart = Cart::with(['items.product' => function ($query) {
            $query->with(['productImages' => function ($q) {
                $q->orderBy('id', 'asc');
            }]);
        }])->where('user_id', $user->id)->first();

        if (!$cart) {
            return response()->json([
                'status' => 'success',
                'message' => 'Keranjang Anda kosong.',
                'data' => [
                    'items' => [],
                    'total_price' => 0,
                ]
            ], 200);
        }

        // Hitung total harga
        $totalPrice = $cart->items->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        // Transform data untuk frontend
        $items = $cart->items->map(function ($item) {
            // Ambil gambar pertama
            $imageUrl = $item->product->productImages->first() ? $item->product->productImages->first()->image_url : null;

            return [
                'id' => $item->id,
                'cart_id' => $item->cart_id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
                'product' => [
                    'id' => $item->product->id,
                    'product_name' => $item->product->product_name,
                    'price' => $item->product->price,
                    'rating' => $item->product->rating,
                    'stock' => $item->product->stock,
                    'information_product' => $item->product->information_product,
                    'specification_product' => $item->product->spesification_product,
                    'category_id' => $item->product->category_id,
                    'type_id' => $item->product->type_id,
                    'brand_id' => $item->product->brand_id,
                    'image_url' => $imageUrl,
                ],
            ];
        });

        $totalItems = $cart->items()->sum('quantity');

        return response()->json([
            'status' => 'success',
            'message' => 'Data keranjang berhasil diambil.',
            'data' => [
                'items' => $items,
                'total_price' => $totalPrice,
                'total_items' => $totalItems,
            ]
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:product,id',
            'quantity'   => 'sometimes|integer|min:1',
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized.'], 401);
        }
        $productId = $request->product_id;
        $quantity = $request->quantity ?? 1;

        // Cari atau buat keranjang untuk user
        $cart = Cart::firstOrCreate(
            ['user_id' => $user->id],
            ['cart_status' => 'active'],
        );

        // Cari produk untuk mendapatkan harga saat ini
        $product = Product::findOrFail($productId);

        // Cek apika produk sudah ada di keranjang
        $cartItem = CartItems::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            // Jika sudah ada, update quantity
            $cartItem->quantity += $quantity;
            $cartItem->save();
            $message = "Jumlah produk di keranjang berhasil diperbarui.";
        } else {
            // Jika belum ada, buat item baru
            CartItems::create([
                'cart_id'    => $cart->id,
                'product_id' => $productId,
                'quantity'   => $quantity,
                'price'      => $product->price,
            ]);
            $message = "Produk berhasil ditambahkan ke keranjang.";
        }

        return response()->json([
            'status' => 'success',
            'message' => $message,
        ], 201); // 201 Created
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

    public function update(Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required|integer|exists:cart_items,id',
            'quantity'     => 'required|integer|min:1',
        ]);

        $user = $request->user();
        $cartItemId = $request->cart_item_id;
        $newQuantity = $request->quantity;

        // Cari cart item yang dimiliki user ini
        $cartItem = CartItems::whereHas('cart', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->findOrFail($cartItemId);

        // Update quantity
        $cartItem->quantity = $newQuantity;
        $cartItem->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Quantity berhasil diperbarui.',
            'data' => [
                'cart_item_id' => $cartItem->id,
                'new_quantity' => $cartItem->quantity,
                'subtotal' => $cartItem->quantity * $cartItem->price,
            ]
        ], 200);
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required|integer|exists:cart_items,id',
        ]);

        $user = $request->user();
        $cartItemId = $request->cart_item_id;

        // Cari cart item yang dimiliki user ini
        $cartItem = CartItems::whereHas('cart', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->findOrFail($cartItemId);

        // Hapus item
        $cartItem->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Produk berhasil dihapus dari keranjang.',
        ], 200);
    }

    public function deleteAll(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized.'], 401);
        }

        CartItems::whereHas('cart', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Semua produk berhasil dihapus dari keranjang.',
        ], 200);
    }
}
