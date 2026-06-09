<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // Ambil semua order diurutkan berdasarkan pesanan terbaru
        $orders = Order::orderBy('created_at', 'desc')->get();

        // Hitung total pendapatan secara dinamis (hanya dari transaksi sukses)
        $totalRevenue = Order::where('status', 'success')->sum('amount');

        // Hitung jumlah pesanan baru yang butuh diproses (status pembayaran sukses dan status pesanan Diproses/Menunggu)
        $newOrdersCount = Order::where('status', 'success')
            ->whereIn('order_status', ['Diproses', 'Menunggu'])
            ->count();

        return view('admin', compact('orders', 'totalRevenue', 'newOrdersCount'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $request->validate([
            'order_status' => 'required|string|in:Diproses,Siap Dikirim,Dikirim,Menunggu,Dibatalkan',
        ]);

        $order = Order::findOrFail($id);
        $order->order_status = $request->order_status;
        $order->save();

        return redirect()->back()->with('success', 'Status pesanan ' . $order->order_id . ' berhasil diperbarui menjadi ' . $request->order_status . '.');
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $orderId = $order->order_id;
        $order->delete();

        return redirect()->back()->with('success', 'Pesanan ' . $orderId . ' berhasil dihapus dari database.');
    }
}
