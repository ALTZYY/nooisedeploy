<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        // Catatan: proyek kamu saat ini pakai login custom yang menyimpan session user_email.
        // Jadi Auth::user() belum tentu berfungsi.
        // Untuk konsisten, kita ambil user dari session user_email.

        $request->validate([
            'product_id' => ['required'],
            'quantity' => ['nullable','integer','min:1'],
        ]);

        $userEmail = $request->session()->get('user_email');
        if (!$userEmail) {
            return redirect('/login');
        }

        $user = \App\Models\User::where('email', $userEmail)->firstOrFail();

        $productId = $request->input('product_id');
        $quantity = (int) ($request->input('quantity') ?? 1);

        $product = Product::findOrFail($productId);

        $cart = Cart::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($cart) {
            $cart->quantity += $quantity;
            $cart->save();
        } else {
            Cart::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Produk berhasil ditambahkan ke keranjang.']);
        }

        return redirect()->route('halaman.about'); // nanti kita map route keranjang
    }

    public function index(Request $request)
    {
        $userEmail = $request->session()->get('user_email');
        if (!$userEmail) {
            return redirect('/login');
        }

        $user = \App\Models\User::where('email', $userEmail)->firstOrFail();

        $carts = Cart::with('product')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'asc')
            ->get();

        $recSkus = ['SKU-NEVER-ENOUGH-DC', 'SKU-MORE-LIFE-DR', 'SKU-CASE-STUDY-01-DC', 'SKU-SOS-SZA', 'SKU-GKMC'];
        $recommendedProducts = \App\Models\Product::whereIn('sku', $recSkus)->get();

        return view('keranjang', [
            'carts' => $carts,
            'recommendedProducts' => $recommendedProducts,
        ]);
    }

    public function updateQuantity(Request $request)
    {
        $userEmail = $request->session()->get('user_email');
        if (!$userEmail) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $user = \App\Models\User::where('email', $userEmail)->first();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }

        $request->validate([
            'cart_id' => 'required|integer',
            'action' => 'required|in:increment,decrement'
        ]);

        $cart = Cart::where('id', $request->cart_id)->where('user_id', $user->id)->first();
        if (!$cart) {
            return response()->json(['success' => false, 'message' => 'Cart item not found'], 404);
        }

        if ($request->action === 'increment') {
            $cart->quantity += 1;
            $cart->save();
        } elseif ($request->action === 'decrement') {
            $cart->quantity -= 1;
            if ($cart->quantity <= 0) {
                $cart->delete();
            } else {
                $cart->save();
            }
        }

        return response()->json([
            'success' => true,
            'new_quantity' => $cart->quantity,
            'is_deleted' => $cart->quantity <= 0
        ]);
    }
}

