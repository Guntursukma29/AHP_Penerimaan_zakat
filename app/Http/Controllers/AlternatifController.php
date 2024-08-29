<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use Illuminate\Http\Request;
use App\Models\PenerimaanZakat;

class AlternatifController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dataPenerimaZakat = PenerimaanZakat::all();
        $kriteria = Kriteria::with('subKriteria')->get();
        return view('data.alternatif', compact('dataPenerimaZakat', 'kriteria'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $dataPenerimaZakat = PenerimaanZakat::findOrFail($request->data_penerima_zakat_id);
    
        // Hapus sub-kriteria yang lama jika ada
        $dataPenerimaZakat->subKriteria()->detach(); // Use detach for many-to-many
    
        // Simpan nilai sub-kriteria baru
        foreach ($request->sub_kriteria as $kriteria_id => $sub_kriteria_id) {
            if ($sub_kriteria_id) {  // Pastikan sub_kriteria_id tidak kosong
                $subKriteria = \App\Models\SubKriteria::find($sub_kriteria_id);
                if ($subKriteria) {
                    $dataPenerimaZakat->subKriteria()->attach($sub_kriteria_id, [
                        'kriteria_id' => $kriteria_id,  // Ensure kriteria_id is included
                        'nilai' => $this->getSubKriteriaValue($sub_kriteria_id),
                    ]);
                }
            }
        }
    
        return redirect()->back()->with('success', 'Sub Kriteria values updated successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function getSubKriteriaValue($sub_kriteria_id)
{
    // Contoh logika untuk mendapatkan nilai berdasarkan sub_kriteria_id
    $subKriteria = \App\Models\SubKriteria::find($sub_kriteria_id);
    
    // Pastikan sub_kriteria ditemukan
    if ($subKriteria) {
        return $subKriteria->nilai;  // Atau logika lain yang sesuai
    }

    // Jika sub_kriteria tidak ditemukan, kembalikan nilai default
    return 0;  // Contoh nilai default
}

}
