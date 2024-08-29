<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PenerimaanZakat;

class PenerimaanZakatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PenerimaanZakat::create([
            'nik' => '1234567890123456',
            'nama' => 'Ahmad',
            'alamat' => 'Jl. Sejahtera No. 1',
            'asnaf' => 'Fakir',
        ]);

        PenerimaanZakat::create([
            'nik' => '2345678901234567',
            'nama' => 'Budi',
            'alamat' => 'Jl. Harmoni No. 2',
            'asnaf' => 'Miskin',
        ]);

        PenerimaanZakat::create([
            'nik' => '3456789012345678',
            'nama' => 'Cahya',
            'alamat' => 'Jl. Merdeka No. 3',
            'asnaf' => 'Amil',
        ]);

        PenerimaanZakat::create([
            'nik' => '4567890123456789',
            'nama' => 'Dewi',
            'alamat' => 'Jl. Cempaka No. 4',
            'asnaf' => 'Muallaf',
        ]);

        PenerimaanZakat::create([
            'nik' => '5678901234567890',
            'nama' => 'Eka',
            'alamat' => 'Jl. Kemuning No. 5',
            'asnaf' => 'Gharim',
        ]);
    }
}
