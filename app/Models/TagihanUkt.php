<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagihanUkt extends Model
{
    use HasFactory;

    protected $table = 'tagihan_ukt';

    protected $fillable = [
        'mahasiswa_id',
        'tahun_akademik',
        'semester',
        'nominal',
        'status',
        'order_id',
        'payment_link',
        'mayar_transaction_id',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'nominal' => 'integer',
            'paid_at' => 'datetime',
        ];
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function scopeUnpaid($query)
    {
        return $query->where('status', 'unpaid');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function getFormattedNominalAttribute(): string
    {
        return 'Rp ' . number_format($this->nominal, 0, ',', '.');
    }
}
