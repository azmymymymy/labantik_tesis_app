<?php

namespace App\Http\Controllers\DataPenelitian;

use App\Http\Controllers\Controller;
use App\Imports\AngketMotivasiImport;
use App\Models\Angket_motivasi;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AngketMotivasiController extends Controller
{
    public function index()
    {
        $dataAngket = Angket_motivasi::all();
        return view('data_penelitian.angket_motivasi.index', compact('dataAngket'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'data_motivasi' => 'required|file|mimes:xlsx,xls,csv',
        ]);
        $filePath = $request->file('data_motivasi')->store('temp');
        Excel::import(new AngketMotivasiImport($filePath), $filePath);

        return redirect()->route('angket-motivasi.index')->with('success', 'Angket Motivasi berhasil disimpan.');
    }

    public function edit($id)
    {
        $angketMo = Angket_motivasi::findOrFail($id);
        return view('data_penelitian.angket_motivasi.edit', compact('angketMo'));
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
            'pertanyaan_8' => 'required|numeric|max:5|min:1',
            'pertanyaan_9' => 'required|numeric|max:5|min:1',
            'pertanyaan_10' => 'required|numeric|max:5|min:1',
            'total' => 'required|numeric|min:0',
        ]);
        $angketMo = Angket_motivasi::findOrFail($id);
        $angketMo->update($request->all());

        return redirect()->route('angket-motivasi.index')->with('success', 'Angket Motivasi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $angketMo = Angket_motivasi::findOrFail($id);
        $angketMo->delete();

        return redirect()->route('angket-motivasi.index')->with('success', 'Angket Motivasi berhasil dihapus.');
    }
}
