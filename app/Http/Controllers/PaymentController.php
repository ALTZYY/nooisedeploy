<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function checkout(Request $request)
    {
        // 1. Ambil user email dari session
        $userEmail = $request->session()->get('user_email');
        if (!$userEmail) {
            return response()->json(['success' => false, 'message' => 'Silakan login terlebih dahulu.'], 401);
        }

        $user = User::where('email', $userEmail)->first();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User tidak ditemukan.'], 404);
        }

        // 2. Validasi input
        $request->validate([
            'email' => 'required|email',
            'address' => 'required|string',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
            'premium_protection' => 'nullable|boolean',
        ]);

        // 3. Ambil item keranjang belanja
        $carts = Cart::with('product')->where('user_id', $user->id)->get();
        if ($carts->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Keranjang belanja Anda kosong.'], 400);
        }

        // 4. Hitung total biaya
        $totalCart = 0;
        $item_details = [];

        foreach ($carts as $cart) {
            $totalCart += (int) $cart->product->price * (int) $cart->quantity;
            $item_details[] = [
                'id' => 'PROD-' . $cart->product_id,
                'price' => (int) $cart->product->price,
                'quantity' => (int) $cart->quantity,
                'name' => substr($cart->product->name, 0, 50),
            ];
        }

        $adminFee = 181;
        $item_details[] = [
            'id' => 'FEE-ADMIN',
            'price' => $adminFee,
            'quantity' => 1,
            'name' => 'Biaya Admin',
        ];

        $premiumProtectionFee = 0;
        $isPremium = $request->input('premium_protection') ? true : false;
        if ($isPremium) {
            $premiumProtectionFee = 5000;
            $item_details[] = [
                'id' => 'FEE-PROTECTION',
                'price' => $premiumProtectionFee,
                'quantity' => 1,
                'name' => 'Perlindungan Transaksi',
            ];
        }

        $grossAmount = $totalCart + $adminFee + $premiumProtectionFee;

        // 5. Buat Unique Order ID
        $orderId = 'ORDER-' . time() . '-' . rand(100, 999);

        // 6. Konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');

        $transaction_details = [
            'order_id' => $orderId,
            'gross_amount' => $grossAmount,
        ];

        $customer_details = [
            'first_name' => $user->name,
            'email' => $request->input('email', $user->email),
        ];

        $payload = [
            'transaction_details' => $transaction_details,
            'item_details' => $item_details,
            'customer_details' => $customer_details,
        ];

        try {
            // Dapatkan token Snap dari Midtrans
            $snapToken = Snap::getSnapToken($payload);

            // Gabungkan nama album dari keranjang
            $albumNames = $carts->map(function ($cart) {
                return $cart->product->name . ' (' . $cart->quantity . 'x)';
            })->implode(', ');

            // Simpan data order ke database dengan status pending
            $order = Order::create([
                'user_id' => $user->id,
                'order_id' => $orderId,
                'amount' => $grossAmount,
                'status' => 'pending',
                'email' => $request->input('email'),
                'address' => $request->input('address'),
                'latitude' => $request->input('latitude'),
                'longitude' => $request->input('longitude'),
                'premium_protection' => $isPremium,
                'snap_token' => $snapToken,
                'album_title' => $albumNames,
                'order_status' => 'Menunggu',
            ]);

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'order_id' => $orderId,
                'amount' => $grossAmount,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghubungkan ke Midtrans: ' . $e->getMessage()
            ], 500);
        }
    }

    public function success(Request $request)
    {
        $userEmail = $request->session()->get('user_email');
        if (!$userEmail) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $user = User::where('email', $userEmail)->first();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User tidak ditemukan.'], 404);
        }

        $request->validate([
            'order_id' => 'required|string',
        ]);

        $order = Order::where('order_id', $request->order_id)
            ->where('user_id', $user->id)
            ->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order tidak ditemukan.'], 404);
        }

        // Update status order menjadi success
        $order->status = 'success';
        $order->order_status = 'Diproses';
        $order->save();

        // Kosongkan keranjang belanja milik user setelah pembayaran berhasil
        Cart::where('user_id', $user->id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran dikonfirmasi dan keranjang telah dibersihkan.'
        ]);
    }

    public function callback(Request $request)
    {
        $serverKey = config('services.midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed == $request->signature_key) {
            $order = Order::where('order_id', $request->order_id)->first();
            if ($order) {
                $transactionStatus = $request->transaction_status;
                if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                    $order->status = 'success';
                    $order->order_status = 'Diproses';
                } elseif ($transactionStatus == 'pending') {
                    $order->status = 'pending';
                    $order->order_status = 'Menunggu';
                } elseif ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
                    $order->status = 'failed';
                    $order->order_status = 'Dibatalkan';
                }
                $order->save();
            }
            return response()->json(['status' => 'ok']);
        }

        return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 403);
    }
}
