<?php

namespace App\Http\Controllers;

use App\Models\Payments;
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
        //
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
        ];

        // 6. Buat Snap Token Midtrans
        $snapToken = Snap::getSnapToken($params);

        // 7. Kirim token ke React — TIDAK SIMPAN KE DB DI SINI
        return response()->json([
            'token' => $snapToken,
            'order_id' => $orderId,
            'user_id' => $user->id, // kirim user_id jika perlu di callback nanti
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
            } else {
                Log::info("Creating new transaction for Order ID: {$orderId}");

                // Buat transaksi baru
                $transaction = Transaction::create([
                    'user_id' => $userId,
                    'transaction_code' => $orderId,
                    'total_amount' => (int) $grossAmount,
                    'transaction_status' => $transactionStatus,
                    'payment_method' => $paymentType,
                    'midtrans_order_id' => $orderId,
                    'paid_at' => ($transactionStatus == 'settlement') ? now() : null,
                ]);

                // Buat payment baru
                Payments::create([
                    'transaction_id' => $transaction->id,
                    'midtrans_transaction_id' => $notif->transaction_id,
                    'payment_type' => $paymentType,
                    'transaction_status' => $transactionStatus,
                    'gross_amount' => (int) $grossAmount,
                    'raw_response' => json_encode($payload),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Midtrans Notification Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        }

        return response()->json(['status' => 'success']);
    }
}
