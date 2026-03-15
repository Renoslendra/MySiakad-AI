<?php

namespace App\Services;

use App\Models\TagihanUkt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MayarPaymentService
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.mayar.base_url'), '/');
        $this->apiKey = config('services.mayar.api_key');
    }

    /**
     * Create a payment link via Mayar Headless API.
     *
     * @return array{success: bool, link?: string, transaction_id?: string, error?: string}
     */
    public function createPaymentLink(TagihanUkt $tagihan, string $name, string $email, string $mobile = ''): array
    {
        $redirectUrl = url('/mahasiswa/tagihan/callback?order_id=' . $tagihan->order_id);

        try {
            $response = Http::withToken($this->apiKey)
                ->timeout(30)
                ->post("{$this->baseUrl}/payment/create", [
                    'name'        => $name,
                    'email'       => $email,
                    'amount'      => $tagihan->nominal,
                    'mobile'      => $mobile ?: '0000000000',
                    'redirectURL' => $redirectUrl,
                    'description' => "Pembayaran UKT {$tagihan->tahun_akademik} - {$tagihan->semester} ({$tagihan->order_id})",
                    'expiredAt'   => now()->addDays(7)->toIso8601String(),
                ]);

            if ($response->successful() && $response->json('statusCode') === 200) {
                $data = $response->json('data');

                return [
                    'success'        => true,
                    'link'           => $data['link'],
                    'transaction_id' => $data['transactionId'] ?? $data['transaction_id'] ?? null,
                ];
            }

            Log::error('Mayar API error', [
                'status'   => $response->status(),
                'response' => $response->json(),
                'order_id' => $tagihan->order_id,
            ]);

            return [
                'success' => false,
                'error'   => $response->json('messages') ?? 'Gagal membuat payment link.',
            ];
        } catch (\Exception $e) {
            Log::error('Mayar API exception', [
                'message'  => $e->getMessage(),
                'order_id' => $tagihan->order_id,
            ]);

            return [
                'success' => false,
                'error'   => 'Terjadi kesalahan saat menghubungi gateway pembayaran.',
            ];
        }
    }
}
