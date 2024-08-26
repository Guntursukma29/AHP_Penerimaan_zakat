<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\SubKriteria;
use Illuminate\Http\Request;

class SubKriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all criteria with related sub-kriteria
        $kriteria = Kriteria::with('subKriteria')->get();
        return view('data.sub_kriteria', compact('kriteria'));
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
    public function store(Request $request, $kriteriaId)
    {
        $request->validate([
            'sub_kriteria_name' => 'required|string|max:255',
            'nilai' => 'required|integer',
        ]);

        SubKriteria::create([
            'kriteria_id' => $kriteriaId,
            'sub_kriteria_name' => $request->sub_kriteria_name,
            'nilai' => $request->nilai,
        ]);

        return redirect()->route('subkriteria.index')->with('success', 'Sub Kriteria added successfully');
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
    public function update(Request $request, $subKriteriaId)
    {
        $request->validate([
            'sub_kriteria_name' => 'required|string|max:255',
            'nilai' => 'required|integer',
        ]);
    
        $subKriteria = SubKriteria::findOrFail($subKriteriaId);
    
        // Check if the Kriteria exists
        $kriteria = Kriteria::find($subKriteria->kriteria_id);
        if (!$kriteria) {
            return redirect()->route('subkriteria.index')->with('error', 'Kriteria not found');
        }
    
        $subKriteria->update([
            'sub_kriteria_name' => $request->sub_kriteria_name,
            'nilai' => $request->nilai,
        ]);
        // Redirect with success message
        return redirect()->route('subkriteria.index')->with('success', 'Sub Kriteria updated successfully');
    }
    

    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($subKriteriaId)
    {
 // Temukan SubKriteria berdasarkan ID
    $subKriteria = SubKriteria::findOrFail($subKriteriaId);

 // Hapus SubKriteria
 $subKriteria->delete();       
  return redirect()->route('subkriteria.index')->with('success', 'Sub Kriteria deleted successfully');
    }
}
