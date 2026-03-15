<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\TagihanUkt;
use App\Models\TahunAkademik;
use App\Services\MayarPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TagihanController extends Controller
{
    public function index()
    {
        $mahasiswa = Auth::user()->mahasiswa;

        if (!$mahasiswa) {
            abort(403, 'Unauthorized');
        }

        $tagihan = TagihanUkt::where('mahasiswa_id', $mahasiswa->id)
            ->orderByDesc('created_at')
            ->get();

        return view('mahasiswa.tagihan.index', compact('mahasiswa', 'tagihan'));
    }

    /**
     * Generate Mayar payment link and redirect mahasiswa to checkout.
     */
    public function bayar(Request $request, MayarPaymentService $mayarService)
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;

        if (!$mahasiswa) {
            abort(403, 'Unauthorized');
        }

        $activeTA = TahunAkademik::where('is_active', true)->first();

        // Find or create unpaid tagihan for active semester
        $tagihan = TagihanUkt::firstOrCreate(
            [
                'mahasiswa_id'   => $mahasiswa->id,
                'tahun_akademik' => $activeTA?->tahun ?? date('Y'),
                'semester'       => $activeTA?->semester ?? 'Ganjil',
                'status'         => 'unpaid',
            ],
            [
                'nominal'  => 3500000,
                'order_id' => 'UKT-' . $mahasiswa->nim . '-' . Str::random(8),
            ]
        );

        // If already has a payment link, redirect directly
        if ($tagihan->payment_link) {
            return redirect()->away($tagihan->payment_link);
        }

        // Create payment link via Mayar
        $result = $mayarService->createPaymentLink(
            tagihan: $tagihan,
            name: $user->name,
            email: $user->email,
        );

        if (!$result['success']) {
            return back()->with('error', $result['error']);
        }

        // Save payment link & transaction to database
        $tagihan->update([
            'payment_link'         => $result['link'],
            'mayar_transaction_id' => $result['transaction_id'],
        ]);

        return redirect()->away($result['link']);
    }

    /**
     * Callback page after Mayar checkout (redirect target).
     */
    public function callback(Request $request)
    {
        $orderId = $request->query('order_id');

        $tagihan = null;
        if ($orderId) {
            $tagihan = TagihanUkt::where('order_id', $orderId)->first();
        }

        return view('mahasiswa.tagihan.callback', compact('tagihan'));
    }
}
