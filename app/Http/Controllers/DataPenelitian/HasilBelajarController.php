<?php

namespace App\Http\Controllers\DataPenelitian;

use App\Http\Controllers\Controller;
use App\Imports\HasilBelajarImport;
use App\Models\Hasil_belajar;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class HasilBelajarController extends Controller
{
    public function index()
    {
        $dataHasilBelajar = Hasil_belajar::all();
        return view('data_penelitian.hasil_belajar.index', compact('dataHasilBelajar'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'data_hasilBelajar' => 'required|file|mimes:xlsx,xls,csv|max:2048',
        ]);
        $file = $request->file('data_hasilBelajar')->store('temp');
        Excel::import(new HasilBelajarImport($file), $file);
        return redirect()->route('hasil-belajar.index')->with('success', 'Data berhasil disimpan!');
    }

    public function edit($id)
    {
        $hasilBelajar = Hasil_belajar::findOrFail($id);
        return view('data_penelitian.hasil_belajar.edit', compact('hasilBelajar'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'kelas_id' => 'required|exists:kelas,id',
            'pretest' => 'required|numeric',
            'posttest' => 'required|numeric',
        ]);

        $hasilBelajar = Hasil_belajar::findOrFail($id);
        $hasilBelajar->update($request->all());

        return redirect()->route('hasil-belajar.index')->with('success', 'Data hasil belajar berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $hasilBelajar = Hasil_belajar::findOrFail($id);
        $hasilBelajar->delete();

        return redirect()->route('hasil-belajar.index')->with('success', 'Data hasil belajar berhasil dihapus!');
    }
}
