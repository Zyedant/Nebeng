<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\WithdrawalHistory;
use App\Models\PartnerPostWithdrawalHistory;
use App\Models\Partner;
use App\Models\PartnerPost;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class WithdrawalController extends Controller
{
    public function partnerIndex(Request $request)
    {
        $withdrawals = WithdrawalHistory::with(['partner.user'])
            ->when($request->search, function($query, $search) {
                $query->whereHas('partner.user', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            })
            ->when($request->status, function($query, $status) {
                if ($status === 'selesai') {
                    $query->where('status', 'Diterima');
                }
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('withdrawals.partner.index', compact('withdrawals'));
    }

    public function partnerPostIndex(Request $request)
    {
        $withdrawals = PartnerPostWithdrawalHistory::with(['partnerPost.user'])
            ->when($request->search, function($query, $search) {
                $query->whereHas('partnerPost', function($q) use ($search) {
                    $q->where('terminal_name', 'like', "%{$search}%");
                })
                ->orWhereHas('partnerPost.user', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            })
            ->when($request->status, function($query, $status) {
                if ($status === 'selesai') {
                    $query->where('status', 'Diterima');
                }
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('withdrawals.partner-post.index', compact('withdrawals'));
    }

    public function partnerShow($id)
    {
        $withdrawal = WithdrawalHistory::with(['partner.user', 'partner.vihecles'])
            ->findOrFail($id);

        return view('withdrawals.partner.show', compact('withdrawal'));
    }

    public function partnerPostShow($id)
    {
        $withdrawal = PartnerPostWithdrawalHistory::with([
                'partnerPost.user', 
                'partnerPost.province', 
                'partnerPost.regency', 
                'partnerPost.district'
            ])
            ->findOrFail($id);

        return view('withdrawals.partner-post.show', compact('withdrawal'));
    }

    public function partnerUpdate(Request $request, $id)
    {
        $withdrawal = WithdrawalHistory::with('partner')->findOrFail($id);

        $request->validate([
            'status' => 'required|in:Diterima,Ditolak'
        ]);

        DB::beginTransaction();
        
        try {
            $withdrawal->status = $request->status;

            if ($request->status === 'Diterima') {
                $proofPath = $this->generateTransferProof($withdrawal, 'partner');
                $withdrawal->transfer_proof = $proofPath;
                
                $partner = $withdrawal->partner;
                $currentBalance = (float) ($partner->balance ?? 0);
                $withdrawalAmount = (float) $withdrawal->amount;
                $newBalance = $currentBalance + $withdrawalAmount;
                
                $partner->balance = (string) $newBalance;
                $partner->save();
            }

            $withdrawal->save();

            DB::commit();

            $message = $request->status === 'Diterima' 
                ? 'Pencairan dana berhasil disetujui dan saldo partner bertambah' 
                : 'Pencairan dana ditolak';

            return redirect()
                ->route('withdrawals.partner.show', $id)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->route('withdrawals.partner.show', $id)
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function partnerPostUpdate(Request $request, $id)
    {
        $withdrawal = PartnerPostWithdrawalHistory::with('partnerPost')->findOrFail($id);

        $request->validate([
            'status' => 'required|in:Diterima,Ditolak'
        ]);

        DB::beginTransaction();
        
        try {
            $withdrawal->status = $request->status;

            if ($request->status === 'Diterima') {
                $proofPath = $this->generateTransferProof($withdrawal, 'partner-post');
                $withdrawal->transfer_proof = $proofPath;
                
                $partnerPost = $withdrawal->partnerPost;
                $currentBalance = (float) ($partnerPost->balance ?? 0);
                $withdrawalAmount = (float) $withdrawal->amount;
                $newBalance = $currentBalance + $withdrawalAmount;
                
                $partnerPost->balance = (string) $newBalance;
                $partnerPost->save();
            }

            $withdrawal->save();

            DB::commit();

            $message = $request->status === 'Diterima' 
                ? 'Pencairan dana berhasil disetujui dan saldo pos mitra bertambah' 
                : 'Pencairan dana ditolak';

            return redirect()
                ->route('withdrawals.partner-post.show', $id)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->route('withdrawals.partner-post.show', $id)
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function generateTransferProof($withdrawal, $type)
    {
        if (!view()->exists('withdrawals.proof-template')) {
            throw new \Exception('View withdrawals.proof-template tidak ditemukan');
        }

        $data = [
            'withdrawal' => $withdrawal,
            'type' => $type,
            'generated_at' => now(),
            'transaction_id' => 'NEBENG-' . strtoupper(substr(md5($withdrawal->id . time()), 0, 6)),
            'invoice_number' => 'INV/' . now()->format('Ymd') . '/' . str_pad($withdrawal->id, 10, '0', STR_PAD_LEFT)
        ];

        $pdf = Pdf::loadView('withdrawals.proof-template', $data);
        
        $pdf->setPaper('a4', 'portrait');

        $filename = 'transfer-proof-' . $withdrawal->id . '-' . time() . '.pdf';
        $filepath = 'transfer-proofs/' . $filename;

        Storage::disk('public')->put($filepath, $pdf->output());

        return $filepath;
    }
}