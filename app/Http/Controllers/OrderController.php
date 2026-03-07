<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    // (3) Customer batal pesanan
    public function cancel(Request $request, $orderId)
    {
        // TODO: ambil order & ubah status jadi cancelled
        // $order = Order::findOrFail($orderId);
        // $order->update(['status' => 'cancelled']);

        log_activity(
            'cancel',
            auth()->user()->name.' membatalkan pesanan',
            'Pada halaman pesanan',
            'pesanan',
            'Order',
            (int) $orderId
        );

        return back()->with('success', 'Pesanan dibatalkan.');
    }
}
