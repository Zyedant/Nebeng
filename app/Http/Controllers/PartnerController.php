<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\Partnervihecle;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $partners = Partner::with(['user', 'vihecles'])
            ->when($search, function($query) use ($search) {
                return $query->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone_number', 'like', "%{$search}%");
                })
                ->orWhere('id_fullname', 'like', "%{$search}%")
                ->orWhere('id_number', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $partnervihecles = collect();
        foreach ($partners as $partner) {
            if ($partner->vihecles->isNotEmpty()) {
                foreach ($partner->vihecles as $vihecle) {
                    $partnervihecles->push([
                        'partner' => $partner,
                        'vihecle' => $vihecle,
                        'user' => $partner->user,
                    ]);
                }
            } else {
                $partnervihecles->push([
                    'partner' => $partner,
                    'vihecle' => null,
                    'user' => $partner->user,
                ]);
            }
        }

        return view('partner.index', compact('partnervihecles', 'partners', 'search'));
    }

    public function show($id)
    {
        $partner = Partner::with(['user', 'vihecles'])->findOrFail($id);
        
        return view('partner.show', compact('partner'));
    }

    public function showvihecle($id)
    {
        $vihecle = Partnervihecle::with(['partner.user'])->findOrFail($id);
        
        return view('partner.vihecle', compact('vihecle'));
    }
}