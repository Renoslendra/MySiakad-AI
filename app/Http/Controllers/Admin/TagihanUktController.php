<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TagihanUkt;
use Illuminate\Http\Request;

class TagihanUktController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();
        $fakultasId = $user->fakultas_id;

        $query = TagihanUkt::with(['mahasiswa.user', 'mahasiswa.prodi']);

        // Faculty-scoped admin can only see their faculty's students
        if (!$isSuperAdmin && $fakultasId) {
            $query->whereHas('mahasiswa.prodi', fn ($q) => $q->where('fakultas_id', $fakultasId));
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by name or NIM
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('mahasiswa', function ($q) use ($search) {
                $q->where('nim', 'like', "%{$search}%")
                  ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        $tagihan = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        // Summary stats
        $statsQuery = TagihanUkt::query();
        if (!$isSuperAdmin && $fakultasId) {
            $statsQuery->whereHas('mahasiswa.prodi', fn ($q) => $q->where('fakultas_id', $fakultasId));
        }

        $stats = [
            'total'       => (clone $statsQuery)->count(),
            'paid'        => (clone $statsQuery)->where('status', 'paid')->count(),
            'unpaid'      => (clone $statsQuery)->where('status', 'unpaid')->count(),
            'total_paid'  => (clone $statsQuery)->where('status', 'paid')->sum('nominal'),
            'total_unpaid' => (clone $statsQuery)->where('status', 'unpaid')->sum('nominal'),
        ];

        return view('admin.tagihan-ukt.index', compact('tagihan', 'stats'));
    }
}
