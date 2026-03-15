<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TagihanUkt;
use App\Models\Mahasiswa;
use App\Models\TahunAkademik;
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

    public function create()
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();
        $fakultasId = $user->fakultas_id;

        $mahasiswaQuery = Mahasiswa::with('user', 'prodi');

        if (!$isSuperAdmin && $fakultasId) {
            $mahasiswaQuery->whereHas('prodi', fn ($q) => $q->where('fakultas_id', $fakultasId));
        }

        $mahasiswa = $mahasiswaQuery->get();
        $tahunAkademik = TahunAkademik::orderBy('tahun', 'desc')->get();

        return view('admin.tagihan-ukt.create', compact('mahasiswa', 'tahunAkademik'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswa,id',
            'tahun_akademik' => 'required|string',
            'semester' => 'required|string',
            'nominal' => 'required|numeric|min:0',
            'payment_link' => 'nullable|url',
        ]);

        TagihanUkt::create([
            'mahasiswa_id' => $request->mahasiswa_id,
            'tahun_akademik' => $request->tahun_akademik,
            'semester' => $request->semester,
            'nominal' => $request->nominal,
            'status' => 'unpaid',
            'order_id' => 'UKT-' . time() . '-' . $request->mahasiswa_id,
            'payment_link' => $request->payment_link,
        ]);

        return redirect()->route('admin.tagihan-ukt.index')->with('success', 'Tagihan UKT berhasil dibuat.');
    }

    public function destroy(TagihanUkt $tagihan)
    {
        if ($tagihan->isPaid()) {
            return back()->with('error', 'Tagihan yang sudah lunas tidak dapat dihapus.');
        }

        $tagihan->delete();

        return redirect()->route('admin.tagihan-ukt.index')->with('success', 'Tagihan UKT berhasil dihapus.');
    }
}
