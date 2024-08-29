<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubKriteria extends Model
{
    use HasFactory;

    protected $table = 'sub_kriteria';

    protected $fillable = ['kriteria_id', 'sub_kriteria_name', 'nilai'];

    /**
     * The kriteria that this sub-kriteria belongs to.
     */
    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }

    /**
     * The penerimaan zakat associated with this sub-kriteria.
     */
    public function penerimaanZakat()
    {
        return $this->belongsToMany(PenerimaanZakat::class, 'penerimaan_zakat_sub_kriteria')
                    ->withPivot('nilai')
                    ->withTimestamps();
    }
}

