<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Kategori;
use App\Models\Ongkir;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $carbons = Carbon::now();

        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('12345'),
            'duplicate' => '12345',
            'telp' => '628111111111',
            'level_id' => '1',
            'alamat' => '-',
            'email_verified_at' => $carbons,
        ]);

        $ongkirs = [
            ['kota' => 'Padang',      'biaya' => 10000],
            ['kota' => 'Pariaman',    'biaya' => 12000],
            ['kota' => 'Solok',       'biaya' => 15000],
            ['kota' => 'Bukittinggi', 'biaya' => 18000],
            ['kota' => 'Payakumbuh',  'biaya' => 20000],
            ['kota' => 'Padang Panjang', 'biaya' => 22000],
            ['kota' => 'Sawahlunto',  'biaya' => 25000],
            ['kota' => 'Painan',      'biaya' => 27000],
            ['kota' => 'Lubuk Basung', 'biaya' => 30000],
            ['kota' => 'Pekanbaru',   'biaya' => 50000],
        ];

        foreach ($ongkirs as $ongkir) {
            Ongkir::create($ongkir);
        }

        // Seeder Kategori
        $kategoris = [
            ['nm_kategori' => 'Baju Anak Laki-laki'],
            ['nm_kategori' => 'Baju Anak Perempuan'],
            ['nm_kategori' => 'Celana Anak'],
            ['nm_kategori' => 'Rok Anak'],
            ['nm_kategori' => 'Jaket Anak'],
            ['nm_kategori' => 'Kaos Anak'],
            ['nm_kategori' => 'Setelan Anak'],
            ['nm_kategori' => 'Pakaian Muslim Anak'],
            ['nm_kategori' => 'Sepatu Anak'],
            ['nm_kategori' => 'Aksesoris Anak'],
        ];

        foreach ($kategoris as $kategori) {
            Kategori::create($kategori);
        }
    }
}
