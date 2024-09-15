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
    public function perbandingan1()
    {
        return $this->hasMany(PerbandinganPekerjaan::class, 'kriteria1_id');
    }

    public function perbandingan2()
    {
        return $this->hasMany(PerbandinganPekerjaan::class, 'kriteria2_id');
    }
    public function perbandinganpekerjaan1()
    {
        return $this->hasMany(PerbandinganPenghasilan::class, 'kriteria1_id');
    }

    public function perbandinganpekerjaan2()
    {
        return $this->hasMany(PerbandinganPenghasilan::class, 'kriteria2_id');
    }
    public function perbandingantempattinggal1()
    {
        return $this->hasMany(PerbandinganTempatTinggal::class, 'kriteria1_id');
    }

    public function perbandingantempattinggal2()
    {
        return $this->hasMany(PerbandinganTempatTinggal::class, 'kriteria2_id');
    }
    public function perbandingantanggungankeluarga1()
    {
        return $this->hasMany(PerbandinganTanggunganKeluarga::class, 'kriteria1_id');
    }

    public function perbandingantanggungankeluarga2()
    {
        return $this->hasMany(PerbandinganTanggunganKeluarga::class, 'kriteria2_id');
    }

    

}
