<?php

namespace App\Http\Controllers\DataPenelitian;

use App\Http\Controllers\Controller;
use App\Imports\ObservasiImport;
use App\Models\Observasi;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ObservasiController extends Controller
{
    public function index()
    {
        $observasi = Observasi::all();
        return view('data_penelitian.observasi.index', compact('observasi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'data_observasi' => 'required|file|mimes:xlsx,csv,xls',
        ]);

        $file = $request->file('data_observasi')->store('temp');
        Excel::import(new ObservasiImport($file), $file);
        return redirect()->route('observasi.index')->with('success', 'Data berhasil disimpan!');
    }
    public function edit($id)
    {
        $observasi = Observasi::findOrFail($id);
        return view('data_penelitian.observasi.edit', compact('observasi'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            
            'pertanyaan_1' => 'required|numeric|max:5|min:1',
            'pertanyaan_2' => 'required|numeric|max:5|min:1',
            'pertanyaan_3' => 'required|numeric|max:5|min:1',
            'pertanyaan_4' => 'required|numeric|max:5|min:1',
            'pertanyaan_5' => 'required|numeric|max:5|min:1',
            'pertanyaan_6' => 'required|numeric|max:5|min:1',
            'pertanyaan_7' => 'required|numeric|max:5|min:1',
        ]);

        $observasi = Observasi::findOrFail($id);
        $observasi->update($request->all());

        return redirect()->route('observasi.index')->with('success', 'Data observasi berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $observasi = Observasi::findOrFail($id);
        $observasi->delete();

        return redirect()->route('observasi.index')->with('success', 'Data observasi berhasil dihapus!');
    }

    
}
