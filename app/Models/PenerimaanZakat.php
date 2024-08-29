<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenerimaanZakat extends Model
{
    use HasFactory;

    protected $table = 'data_penerima_zakat';

    protected $fillable = [
        'nik', 'nama', 'alamat', 'asnaf',
    ];

    public function kriteria()
    {
        return $this->belongsToMany(Kriteria::class, 'kriteria_penerima_zakat')
                    ->withPivot('nilai')
                    ->withTimestamps();
    }

    public function subKriteria()
    {
        return $this->belongsToMany(SubKriteria::class, 'penerimaan_zakat_sub_kriteria')
                    ->withPivot('kriteria_id', 'nilai')
                    ->withTimestamps();
    }

}
