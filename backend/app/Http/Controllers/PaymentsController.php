<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaction;
use App\Models\Payments;
use App\Models\TempOrder;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Midtrans\Notification;
use Midtrans\Snap;
use Midtrans\Config;

class PaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payment = Payments::with('transaction')->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Get data payment successfully!',
            'payments' => $payment
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
        // 1. Set Midtrans Config
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // 2. Validasi input
        $request->validate([
            'amount' => 'required|integer|min:1',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
        ]);

        // 3. Ambil user_id dari auth
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        // 4. Generate order ID
        $orderId = 'ORDER-' . time();

        // 5. Siapkan parameter untuk Snap Token
        $params = [
            'transaction_details' => [
                'order_id'      => $orderId,
                'gross_amount'  => $request->amount,
            ],
            'customer_details' => [
                'first_name' => $request->name,
                'email'      => $request->email,
            ],
            'custom_field1' => $user->id,
            'custom_field2' => $request->product_id ?? null,
            'custom_field3' => $request->quantity ?? null,
        ];

        // 6. Buat Snap Token Midtrans
        $snapToken = Snap::getSnapToken($params);

        // 7. Simpan ke table sementara
        try {
            if ($request->has('cart_items') && is_array($request->cart_items)) {
                foreach ($request->cart_items as $item) {
                    TempOrder::create([
                        'order_id' => $orderId,
                        'user_id' => $user->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                    ]);
                }
            } elseif ($request->has('product_id') && $request->has('quantity')) {
                TempOrder::create([
                    'order_id' => $orderId,
                    'user_id' => $user->id,
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity,
                    'price' => $request->amount,
                ]);
            } else {
                return response()->json(['error' => 'Produk tidak valid'], 400);
            }
        } catch (\Exception $e) {
            Log::error('Error inserting to temp_orders: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }

        // 8. Kirim token ke React
        return response()->json([
            'token' => $snapToken,
            'order_id' => $orderId,
            'user_id' => $user->id,
        ]);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    private function generateTransactionCode()
    {
        $date = now()->format('Ymd');
        $lastTransaction = Transaction::where('transaction_code', 'LIKE', "TRX-{$date}-%")
            ->orderBy('transaction_code', 'desc')
            ->first();

        if ($lastTransaction) {
            // Ekstrak nomor urut dari kode terakhir
            $lastNumber = (int) substr($lastTransaction->transaction_code, -4);
            $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '0001';
        }

        return "TRX-{$date}-{$nextNumber}";
    }

    /**
     * Handle Midtrans notification
     */
    public function handleNotification(Request $request)
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized', true);
        Config::$is3ds = config('midtrans.is_3ds', true);

        try {
            $notif = new Notification();
            $payload = $notif->getResponse();
            $transactionStatus = $notif->transaction_status;
            $orderId = $notif->order_id;
            $paymentType = $notif->payment_type;
            $grossAmount = $notif->gross_amount;
            $userId = $notif->custom_field1;

            // Cari semua temp orders untuk order_id ini
            $tempOrders = TempOrder::where('order_id', $orderId)->get();

            // Cek apakah ada transaksi yang sudah dibuat sebelumnya
            $transaction = Transaction::where('midtrans_order_id', $orderId)->first();

            if ($transaction) {
                $transaction->update([
                    'transaction_status' => $transactionStatus,
                    'payment_method' => $paymentType,
                    'paid_at' => ($transactionStatus == 'settlement') ? now() : $transaction->paid_at,
                ]);

                $payment = Payments::where('transaction_id', $transaction->id)->first();
                if ($payment) {
                    $payment->update([
                        'midtrans_transaction_id' => $notif->transaction_id,
                        'payment_type' => $paymentType,
                        'transaction_status' => $transactionStatus,
                        'raw_response' => json_encode($payload),
                    ]);
                }

                // Hapus semua temp orders ditable sementara
                TempOrder::where('order_id', $orderId)->delete();
            } else {
                // Buat transaksi baru
                $transactionCode = $this->generateTransactionCode();
                $transaction = Transaction::create([
                    'user_id' => $userId,
                    'transaction_code' => $transactionCode,
                    'total_amount' => (int) $grossAmount,
                    'transaction_status' => $transactionStatus,
                    'payment_method' => $paymentType,
                    'midtrans_order_id' => $orderId,
                    'paid_at' => ($transactionStatus == 'settlement') ? now() : null,
                ]);

                // Buat payment
                Payments::create([
                    'transaction_id' => $transaction->id,
                    'midtrans_transaction_id' => $notif->transaction_id,
                    'payment_type' => $paymentType,
                    'transaction_status' => $transactionStatus,
                    'gross_amount' => (int) $grossAmount,
                    'raw_response' => json_encode($payload),
                ]);

                if ($tempOrders->isNotEmpty()) {
                    foreach ($tempOrders as $tempOrder) {
                        DetailTransaction::create([
                            'transaction_id' => $transaction->id,
                            'product_id' => $tempOrder->product_id,
                            'quantity' => $tempOrder->quantity,
                            'price' => (int) $tempOrder->price,
                            'subtotal' => (int) ($tempOrder->price * $tempOrder->quantity),
                        ]);
                    }

                    // Hapus semua temp orders ditable sementara
                    TempOrder::where('order_id', $orderId)->delete();
                } else {
                    $product_id = $request->input('custom_field2');
                    $quantity = $request->input('custom_field3');

                    if ($product_id && $quantity) {
                        $price = (int) ($grossAmount / $quantity);
                        DetailTransaction::create([
                            'transaction_id' => $transaction->id,
                            'product_id' => $product_id,
                            'quantity' => $quantity,
                            'price' => $price,
                            'subtotal' => (int) $grossAmount,
                        ]);
                    } else {
                        Log::warning("No product data found for Order ID: {$orderId} (not saved to detail_transaction)");
                    }
                }
            }

            // kirim status 200 agar Midtrans tidak kirim email error
            return response()->json([
                'status' => 'success',
                'message' => 'added transaaction and payments successfully!',
            ], 200);
        } catch (\Exception $e) {
            Log::error('Midtrans Notification Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 200);
        }
    }
}
