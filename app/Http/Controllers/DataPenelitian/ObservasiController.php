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

    
}
