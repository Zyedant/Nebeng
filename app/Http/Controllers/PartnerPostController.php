<?php

namespace App\Http\Controllers;

use App\Models\PartnerPost;
use App\Models\Province;
use Illuminate\Http\Request;

class PartnerPostController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $provinceId = $request->get('province_id');
        $status = $request->get('status');

        $partnerPosts = PartnerPost::with(['user', 'province', 'regency', 'district'])
            ->when($search, function($query, $search) {
                return $query->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })
                ->orWhere('id_fullname', 'like', "%{$search}%")
                ->orWhere('terminal_name', 'like', "%{$search}%")
                ->orWhere('id_number', 'like', "%{$search}%");
            })
            ->when($provinceId, function($query, $provinceId) {
                return $query->where('terminal_province_id', $provinceId);
            })
            ->when($status && $status !== 'all', function($query, $status) {
                return $query->where('verified_status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $provinces = Province::all();

        return view('partner-post.index', compact('partnerPosts', 'provinces', 'search', 'provinceId', 'status'));
    }

    public function show($id)
    {
        $partnerPost = PartnerPost::with(['user', 'province', 'regency', 'district'])
            ->findOrFail($id);
        
        return view('partner-post.show', compact('partnerPost'));
    }
}