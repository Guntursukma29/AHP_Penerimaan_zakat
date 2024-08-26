<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    use HasFactory;

    protected $table = 'kriteria';

    protected $fillable = ['nama_kriteria','nilai'];

    public function penerimaanZakat()
    {
        return $this->belongsToMany(PenerimaanZakat::class, 'kriteria_penerima_zakat')->withPivot('nilai')->withTimestamps();
    }
    public function subKriteria()
{
    return $this->hasMany(SubKriteria::class);
}

}
