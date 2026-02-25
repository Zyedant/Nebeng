<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['customer.user', 'partner.user', 'payment', 'refund'])
                      ->latest();
        
        if ($request->has('status') && $request->status !== 'Status') {
            $status = $request->status;
            $query->where(function($q) use ($status) {
                if ($status === 'Selesai') {
                    $q->whereHas('payment', function($paymentQuery) {
                        $paymentQuery->where('status', 'Diterima');
                    });
                } elseif ($status === 'Proses') {
                    $q->where(function($q2) {
                        $q2->whereHas('payment', function($paymentQuery) {
                            $paymentQuery->whereIn('status', ['Diproses'])
                                         ->orWhereNull('id');
                        })->orWhereDoesntHave('payment');
                    });
                } elseif ($status === 'Batal') {
                    $q->where(function($q2) {
                        $q2->whereHas('payment', function($paymentQuery) {
                            $paymentQuery->where('status', 'Ditolak');
                        })->orWhereHas('refund', function($refundQuery) {
                            $refundQuery->where('status', 'Diterima');
                        });
                    });
                }
            });
        }
        
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('customer.user', function($customerQuery) use ($search) {
                    $customerQuery->where('name', 'like', "%{$search}%")
                                  ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('partner.user', function($partnerQuery) use ($search) {
                    $partnerQuery->where('name', 'like', "%{$search}%")
                                 ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhere('id', 'like', "%{$search}%");
            });
        }
        
        $transactions = $query->paginate(10);
        
        return view('transactions.index', compact('transactions'));
    }

    public function show($id)
    {
        $transaction = Order::with([
            'customer.user',
            'partner.user',
            'partner.vihecles',
            'departurePost.province',
            'departurePost.regency',
            'departurePost.district',
            'destinationPost.province',
            'destinationPost.regency',
            'destinationPost.district',
            'payment',
            'refund'
        ])->findOrFail($id);
    
        return view('transactions.show', compact('transaction'));
    }

    public function accept($id)
    {
        try {
            DB::beginTransaction();

            $order = Order::with(['payment', 'customer.user', 'partner.user', 'departurePost', 'destinationPost'])->findOrFail($id);

            if (!$order->payment) {
                return redirect()->back()->with('error', 'Transaksi tidak memiliki data pembayaran.');
            }

            if ($order->payment->status !== 'Diproses') {
                return redirect()->back()->with('error', 'Status pembayaran tidak dalam proses. Status saat ini: ' . $order->payment->status);
            }

            $proofPath = $this->generatePaymentProof($order);

            $order->payment->update([
                'status' => 'Diterima',
                'payment_proof' => $proofPath
            ]);

            DB::commit();

            Log::info('Transaksi diterima', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'proof_path' => $proofPath,
                'updated_by' => auth()->id() ?? 'system'
            ]);

            return redirect()->route('transactions.show', $id)
                ->with('success', 'Transaksi berhasil diterima. Bukti pembayaran telah digenerate.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menerima transaksi: ' . $e->getMessage(), [
                'order_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Gagal menerima transaksi: ' . $e->getMessage());
        }
    }

    private function generatePaymentProof($order)
    {
        if (!view()->exists('transactions.payment-proof')) {
            throw new \Exception('View transactions.payment-proof tidak ditemukan');
        }

        $biayaPerjalanan = (float) ($order->payment->payment_amount ?? 0);
        $biayaAdmin = $biayaPerjalanan * 0.1;
        $total = $biayaPerjalanan + $biayaAdmin;

        $data = [
            'order' => $order,
            'transaction_id' => 'TRX-' . str_pad($order->id, 3, '0', STR_PAD_LEFT),
            'order_number' => 'ORD-' . str_pad($order->id, 3, '0', STR_PAD_LEFT),
            'invoice_number' => 'INV/' . now()->format('Ymd') . '/' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
            'customer_name' => $order->customer->user->name ?? 'Customer',
            'partner_name' => $order->partner->user->name ?? 'Driver',
            'departure_terminal' => $order->departurePost->terminal_name ?? '-',
            'destination_terminal' => $order->destinationPost->terminal_name ?? '-',
            'departure_address' => $order->departurePost->terminal_address ?? '-',
            'destination_address' => $order->destinationPost->terminal_address ?? '-',
            'date' => $order->date,
            'time' => $order->time,
            'biaya_perjalanan' => $biayaPerjalanan,
            'biaya_admin' => $biayaAdmin,
            'total' => $total,
            'payment_method' => $order->payment->payment_method_text ?? $order->payment->payment_method ?? 'Transfer Bank',
            'generated_at' => now(),
        ];

        $pdf = Pdf::loadView('transactions.payment-proof', $data);
        
        $pdf->setPaper('a4', 'portrait');

        $filename = 'payment-proof-' . $order->id . '-' . time() . '.pdf';
        $filepath = 'payments/' . $filename;

        Storage::disk('public')->put($filepath, $pdf->output());

        return $filepath;
    }

    public function reject(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $order = Order::with('payment')->findOrFail($id);

            if (!$order->payment) {
                return redirect()->back()->with('error', 'Transaksi tidak memiliki data pembayaran.');
            }

            if ($order->payment->status !== 'Diproses') {
                return redirect()->back()->with('error', 'Status pembayaran tidak dalam proses. Status saat ini: ' . $order->payment->status);
            }

            $request->validate([
                'rejection_reason' => 'required|string|max:500'
            ]);

            $order->payment->update([
                'status' => 'Ditolak'
            ]);

            DB::commit();

            Log::info('Transaksi ditolak', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'updated_by' => auth()->id() ?? 'system',
                'reason' => $request->rejection_reason
            ]);

            return redirect()->route('transactions.show', $id)
                ->with('success', 'Transaksi berhasil ditolak.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menolak transaksi: ' . $e->getMessage(), [
                'order_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Gagal menolak transaksi: ' . $e->getMessage());
        }
    }
}