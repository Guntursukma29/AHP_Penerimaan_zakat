<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenerimaanZakat;

class PenerimaanZakatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    $penerimaanZakat = PenerimaanZakat::with('kriteria')->get();
    // dd($penerimaanZakat);
    return view('data.penerima_zakat', compact('penerimaanZakat'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Optional: You can add logic to show the form for creating new data.
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nik' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'asnaf' => 'required|string|max:255',
            'kriteria' => 'array',
            'kriteria.*' => 'integer',
        ]);

        $penerimaanZakat = PenerimaanZakat::create($validatedData);

        if ($request->has('kriteria')) {
            foreach ($request->kriteria as $kriteria_id => $nilai) {
                $penerimaanZakat->kriteria()->attach($kriteria_id, ['nilai' => $nilai]);
            }
        }

        return redirect()->route('penerimaan-zakat.index')->with('success', 'Data penerima zakat berhasil ditambahkan!');
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PenerimaanZakat $penerimaanZakat)
    {
        // No need to return view here as you are using modals
        return view('data.penerima_zakat', compact('penerimaanZakat'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PenerimaanZakat $penerimaanZakat)
    {
        $request->validate([
            'nik' => 'required',
            'nama' => 'required',
            'alamat' => 'required',
            'asnaf' => 'required',
        ]);

        $pekerjaan = $request->pekerjaan === 'lainnya' ? $request->pekerjaan_lainnya : $request->pekerjaan;

        $penerimaanZakat->update([
            'nik' => $request->nik,
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'asnaf' => $request->asnaf,
        ]);

        return redirect()->route('penerimaan-zakat.index')->with('success', 'Data updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PenerimaanZakat $penerimaanZakat)
    {
        $penerimaanZakat->delete();
        return redirect()->route('penerimaan-zakat.index')->with('success', 'Data deleted successfully');
    }
}
