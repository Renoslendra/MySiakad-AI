<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KehadiranDosen;
use Carbon\Carbon;

class KehadiranDosenSeeder extends Seeder
{
    public function run()
    {
        $dosenId = 1;
        $jadwalId = 1;
        $pertemuanId = 1;
        
        $data = [
            [
                'dosen_id' => $dosenId,
                'jadwal_kuliah_id' => $jadwalId,
                'pertemuan_id' => $pertemuanId,
                'tanggal' => '2026-03-02',
                'jam_masuk' => '07:55:00',
                'jam_keluar' => '09:30:00',
                'status' => 'hadir',
                'keterangan' => 'Pertemuan pertama - Pengenalan Mata Kuliah',
            ],
            [
                'dosen_id' => $dosenId,
                'jadwal_kuliah_id' => $jadwalId,
                'pertemuan_id' => $pertemuanId,
                'tanggal' => '2026-03-09',
                'jam_masuk' => '08:02:00',
                'jam_keluar' => '09:30:00',
                'status' => 'hadir',
                'keterangan' => 'Materi Bab 1',
            ],
            [
                'dosen_id' => $dosenId,
                'jadwal_kuliah_id' => $jadwalId,
                'pertemuan_id' => $pertemuanId,
                'tanggal' => '2026-03-11',
                'jam_masuk' => '08:00:00',
                'jam_keluar' => '09:30:00',
                'status' => 'hadir',
                'keterangan' => 'Latihan Soal',
            ],
            [
                'dosen_id' => $dosenId,
                'jadwal_kuliah_id' => $jadwalId,
                'pertemuan_id' => $pertemuanId,
                'tanggal' => '2026-03-13',
                'jam_masuk' => '07:58:00',
                'jam_keluar' => '09:35:00',
                'status' => 'hadir',
                'keterangan' => 'Diskusi Kelompok',
            ],
            [
                'dosen_id' => $dosenId,
                'jadwal_kuliah_id' => $jadwalId,
                'pertemuan_id' => $pertemuanId,
                'tanggal' => '2026-03-15',
                'jam_masuk' => '08:05:00',
                'jam_keluar' => '09:30:00',
                'status' => 'hadir',
                'keterangan' => 'Evaluasi Bulanan',
            ],
        ];

        foreach ($data as $item) {
            KehadiranDosen::updateOrCreate(
                [
                    'dosen_id' => $item['dosen_id'],
                    'tanggal' => $item['tanggal'],
                ],
                $item
            );
        }
    }
}
