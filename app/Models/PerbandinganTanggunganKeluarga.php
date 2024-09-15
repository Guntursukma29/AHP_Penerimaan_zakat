<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerbandinganTanggunganKeluarga extends Model
{
    use HasFactory;

    protected $table = 'tanggungan_keluarga';

    protected $fillable = [
        'kriteria1_id',
        'kriteria2_id',
        'selected_kriteria_id',
        'nilai',
    ];
    public function kriteria1()
    {
        return $this->belongsTo(PenerimaanZakat::class, 'kriteria1_id');
    }

    public function kriteria2()
    {
        return $this->belongsTo(PenerimaanZakat::class, 'kriteria2_id');
    }

    public function selectedKriteria()
    {
        return $this->belongsTo(PenerimaanZakat::class, 'selected_kriteria_id');
    }
}
