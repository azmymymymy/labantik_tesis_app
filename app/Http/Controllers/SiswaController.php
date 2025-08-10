<?php

namespace App\Http\Controllers;

use App\Imports\SiswaImport;
use Illuminate\Http\Request;
use App\Models\Siswa;
use Maatwebsite\Excel\Facades\Excel;

class SiswaController extends Controller
{
    public function clear()
    {
        Siswa::truncate();
        return redirect()->route('siswa.index')->with('success', 'Semua data siswa berhasil dihapus!');
    }
    public function index()
    {
        $siswas = Siswa::all();
        return view('siswa.index', compact('siswas'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'data_siswa' => 'required|file|mimes:xlsx,xls,csv|max:2048',
        ]);
        $file = $request->file('data_siswa')->store('temp');
        Excel::import(new SiswaImport($file), $file);
        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil ditambahkan!');
    }
    public function edit($id)
    {
        $siswa = Siswa::findOrFail($id);
        return view('siswa.edit', compact('siswa'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nis' => 'required|string|max:50|unique:siswa,nis,' . $id,
            'kelas' => 'required|string|max:50',
            'jenis_kelamin' => 'required|in:L,P',
        ]);
        $siswa = Siswa::findOrFail($id);
        $siswa->update($validated);
        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diupdate!');
    }

    public function destroy($id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->delete();
        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil dihapus!');
    }
}
