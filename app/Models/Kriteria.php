<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    use HasFactory;

    protected $table = 'kriteria';

    protected $fillable = ['nama_kriteria'];

    public function penerimaanZakat()
    {
        return $this->belongsToMany(PenerimaanZakat::class, 'kriteria_penerima_zakat')->withPivot('nilai')->withTimestamps();
    }
    public function subKriteria()
    {
        return $this->hasMany(SubKriteria::class);
    }

    public function perbandingan1()
    {
        return $this->hasMany(PerbandinganKriteria::class, 'kriteria1_id');
    }

    public function perbandingan2()
    {
        return $this->hasMany(PerbandinganKriteria::class, 'kriteria2_id');
    }
}
