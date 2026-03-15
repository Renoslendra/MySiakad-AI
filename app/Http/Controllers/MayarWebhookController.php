<?php

namespace App\Http\Controllers;

use App\Models\TagihanUkt;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MayarWebhookController extends Controller
{
    /**
     * Handle incoming Mayar webhook notifications.
     *
     * Mayar sends a POST with event type and transaction data.
     * We look for 'payment.received' events and mark the matching tagihan as paid.
     */
    public function handle(Request $request): JsonResponse
    {
        $payload = $request->all();

        Log::info('Mayar webhook received', ['payload' => $payload]);

        $event = data_get($payload, 'event.received')
              ?? data_get($payload, 'event');

        if ($event !== 'payment.received') {
            return response()->json(['message' => 'Event ignored.'], 200);
        }

        $data = data_get($payload, 'data', []);

        // Mayar doesn't send back our order_id directly, so we match via
        // description (which we embedded the order_id in) or transaction_id.
        $transactionId = data_get($data, 'id');
        $description   = data_get($data, 'productName', '');
        $status        = data_get($data, 'status');

        // Only process successful payments
        if ($status !== true && $status !== 'true') {
            Log::info('Mayar webhook: payment not successful', ['status' => $status]);
            return response()->json(['message' => 'Payment not successful.'], 200);
        }

        // Strategy 1: Match by mayar_transaction_id
        $tagihan = TagihanUkt::where('mayar_transaction_id', $transactionId)->first();

        // Strategy 2: Extract order_id from description (format: "... (UKT-NIM-XXXXX)")
        if (!$tagihan) {
            if (preg_match('/\((UKT-[^)]+)\)/', $description, $matches)) {
                $tagihan = TagihanUkt::where('order_id', $matches[1])->first();
            }
        }

        // Strategy 3: Match by customer email + amount (last resort)
        if (!$tagihan) {
            $customerEmail = data_get($data, 'customerEmail');
            $amount        = data_get($data, 'amount');

            if ($customerEmail && $amount) {
                $tagihan = TagihanUkt::where('status', 'unpaid')
                    ->where('nominal', $amount)
                    ->whereHas('mahasiswa.user', function ($q) use ($customerEmail) {
                        $q->where('email', $customerEmail);
                    })
                    ->first();
            }
        }

        if (!$tagihan) {
            Log::warning('Mayar webhook: tagihan not found', [
                'transaction_id' => $transactionId,
                'description'    => $description,
            ]);
            return response()->json(['message' => 'Tagihan not found.'], 200);
        }

        if ($tagihan->isPaid()) {
            return response()->json(['message' => 'Already paid.'], 200);
        }

        $tagihan->update([
            'status'               => 'paid',
            'paid_at'              => now(),
            'mayar_transaction_id' => $transactionId,
        ]);

        Log::info('Mayar webhook: tagihan updated to paid', [
            'tagihan_id' => $tagihan->id,
            'order_id'   => $tagihan->order_id,
        ]);

        return response()->json(['message' => 'OK'], 200);
    }
}
