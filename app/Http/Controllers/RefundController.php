<?php

namespace App\Http\Controllers;

use App\Models\Refund;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RefundController extends Controller
{
    public function index(Request $request)
    {
        $refunds = Refund::with([
                'order.customer.user',
                'order.partner.user',
                'order.payment'
            ])
            ->when($request->search, function($query, $search) {
                $query->whereHas('order.customer.user', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('order.partner.user', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('order', function($q) use ($search) {
                    $q->where('id', 'like', "%{$search}%");
                });
            })
            ->when($request->status, function($query, $status) {
                $statusMap = [
                    'Proses' => 'Diproses',
                    'Selesai' => 'Diterima',
                    'Batal' => 'Ditolak'
                ];
                $dbStatus = $statusMap[$status] ?? $status;
                return $query->where('status', $dbStatus);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('refund.index', compact('refunds'));
    }

    public function show($id)
    {
        $refund = Refund::with([
            'order.customer.user',
            'order.partner.user',
            'order.payment'
        ])->findOrFail($id);

        return view('refund.show', compact('refund'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Diproses,Diterima,Ditolak'
        ]);

        DB::beginTransaction();
        
        try {
            $refund = Refund::with(['order.customer', 'order.payment'])->findOrFail($id);
            
            if ($request->status == 'Diterima' && $refund->status != 'Diterima') {
                $paymentAmount = $refund->order->payment->payment_amount ?? 0;
                
                if ($paymentAmount > 0) {
                    $customer = $refund->order->customer;
                    $customer->balance = ($customer->balance ?? 0) + $paymentAmount;
                    $customer->save();
                }
            }
            
            $refund->update([
                'status' => $request->status
            ]);

            DB::commit();

            $statusText = match($request->status) {
                'Diterima' => 'disetujui dan saldo customer telah ditambahkan',
                'Ditolak' => 'ditolak',
                default => 'diproses'
            };

            return redirect()->route('refund.show', $refund->id)
                ->with('success', "Refund berhasil {$statusText}");

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('refund.show', $id)
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}