<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Studio;
use App\Models\Fasilitas;
use App\Models\Promo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin & Pelanggan
        User::create([
            'name' => 'Admin Studio Positif',
            'email' => 'admin@studiopositif.com',
            'password' => Hash::make('admin123'),
            'no_hp' => '081234567890',
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        User::create([
            'name' => 'Rafi Yandri',
            'email' => 'rafi@gmail.com',
            'password' => Hash::make('pelanggan123'),
            'no_hp' => '082211223344',
            'role' => 'pelanggan',
            'status' => 'aktif',
        ]);

        // 2. Master Fasilitas
        $fasilitasList = [
            'AC Dingin',
            'Drum Set Akustik Tama',
            'Ampli Gitar Marshall JCM900',
            'Ampli Bass Ampeg',
            'Keyboard Roland RD-2000',
            'Mixer 16 Channel & Mic Condenser',
            'Peredam Suara Full Akustik',
            'WiFi High Speed & Sofa Ruang Tunggu',
        ];

        $fasilitasIds = [];
        foreach ($fasilitasList as $nama) {
            $f = Fasilitas::create(['nama' => $nama]);
            $fasilitasIds[] = $f->id;
        }

        // 3. Data 6 Studio
        $studios = [
            [
                'nama' => 'Studio A - Reguler Band',
                'deskripsi' => 'Studio latihan band standar dengan akustik ruangan nyaman, cocok untuk latihan rutin band indie maupun sekolah.',
                'kapasitas' => 6,
                'harga_per_jam' => 50000,
                'fasilitas' => [0, 1, 2, 3, 6],
            ],
            [
                'nama' => 'Studio B - Deluxe Acoustic',
                'deskripsi' => 'Ruangan lebih luas dengan peredam akustik premium serta tambahan keyboard stage dan sound monitor jernih.',
                'kapasitas' => 8,
                'harga_per_jam' => 75000,
                'fasilitas' => [0, 1, 2, 3, 4, 6, 7],
            ],
            [
                'nama' => 'Studio C - VIP Recording & Rehearsal',
                'deskripsi' => 'Fasilitas lengkap kelas profesional untuk latihan intensif, live session, maupun tracking demo rekaman.',
                'kapasitas' => 10,
                'harga_per_jam' => 120000,
                'fasilitas' => [0, 1, 2, 3, 4, 5, 6, 7],
            ],
            [
                'nama' => 'Studio D - Vocal & Podcast Booth',
                'deskripsi' => 'Ruang kedap suara khusus latihan vokal solo, duet akustik, dubbing, atau rekaman podcast.',
                'kapasitas' => 4,
                'harga_per_jam' => 40000,
                'fasilitas' => [0, 5, 6, 7],
            ],
            [
                'nama' => 'Studio E - Mini Jamming Room',
                'deskripsi' => 'Studio ekonomis untuk latihan format trio atau akustik dengan perlengkapan ringkas dan siap pakai.',
                'kapasitas' => 5,
                'harga_per_jam' => 45000,
                'fasilitas' => [0, 1, 2, 3, 6],
            ],
            [
                'nama' => 'Studio F - Orchestra & Big Band Hall',
                'deskripsi' => 'Aula latihan berkapasitas besar untuk ensambel, paduan suara, maupun persiapan konser full band.',
                'kapasitas' => 15,
                'harga_per_jam' => 150000,
                'fasilitas' => [0, 1, 2, 3, 4, 5, 6, 7],
            ],
        ];

        foreach ($studios as $data) {
            $attachIds = array_map(fn($idx) => $fasilitasIds[$idx], $data['fasilitas']);
            unset($data['fasilitas']);

            $studio = Studio::create($data);
            $studio->fasilitas()->attach($attachIds);
        }

        // 4. Data Kode Promo
        Promo::create([
            'kode' => 'POSITIF10',
            'tipe_potongan' => 'persen',
            'potongan' => 10,
            'berlaku_mulai' => now()->subDays(5)->toDateString(),
            'berlaku_sampai' => now()->addMonths(3)->toDateString(),
        ]);

        Promo::create([
            'kode' => 'HEMAT15K',
            'tipe_potongan' => 'nominal',
            'potongan' => 15000,
            'berlaku_mulai' => now()->subDays(5)->toDateString(),
            'berlaku_sampai' => now()->addMonths(3)->toDateString(),
        ]);
    }
}