<?php

namespace App\Http\Controllers\DataPenelitian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AngketMinatImport;
use App\Models\AngketMinat;


class AngketMinatController extends Controller
{
    public function index()
    {
        $angketMinat = AngketMinat::all();
        return view('data_penelitian.angket_minat.index', compact('angketMinat'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('data_penelitian.angket_minat.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kelas' => 'required|string|max:255',
            'nilai_1' => 'required|numeric|min:1|max:5',
            'nilai_2' => 'required|numeric|min:1|max:5',
            'nilai_3' => 'required|numeric|min:1|max:5',
            'nilai_4' => 'required|numeric|min:1|max:5',
            'nilai_5' => 'required|numeric|min:1|max:5',
            'nilai_6' => 'required|numeric|min:1|max:5',
            'nilai_7' => 'required|numeric|min:1|max:5',
            'nilai_8' => 'required|numeric|min:1|max:5',
            'nilai_9' => 'required|numeric|min:1|max:5',
            'nilai_10' => 'required|numeric|min:1|max:5',
            'nilai_11' => 'required|numeric|min:1|max:5',
            'nilai_12' => 'required|numeric|min:1|max:5',
            'nilai_13' => 'required|numeric|min:1|max:5',
            'nilai_14' => 'required|numeric|min:1|max:5',
        ]);

        // Hitung total nilai
        $totalNilai = $request->nilai_1 + $request->nilai_2 + $request->nilai_3 +
            $request->nilai_4 + $request->nilai_5 + $request->nilai_6 +
            $request->nilai_7 + $request->nilai_8 + $request->nilai_9 +
            $request->nilai_10 + $request->nilai_11 + $request->nilai_12 +
            $request->nilai_13 + $request->nilai_14;

        AngketMinat::create([
            'nama' => $request->nama,
            'kelas' => $request->kelas,
            'nilai_1' => $request->nilai_1,
            'nilai_2' => $request->nilai_2,
            'nilai_3' => $request->nilai_3,
            'nilai_4' => $request->nilai_4,
            'nilai_5' => $request->nilai_5,
            'nilai_6' => $request->nilai_6,
            'nilai_7' => $request->nilai_7,
            'nilai_8' => $request->nilai_8,
            'nilai_9' => $request->nilai_9,
            'nilai_10' => $request->nilai_10,
            'nilai_11' => $request->nilai_11,
            'nilai_12' => $request->nilai_12,
            'nilai_13' => $request->nilai_13,
            'nilai_14' => $request->nilai_14,
            'total_nilai' => $totalNilai,
        ]);

        return redirect()->route('angket-minat.index')->with('success', 'Data angket minat berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $angketMinat = AngketMinat::findOrFail($id);
        return view('angket-minat.show', compact('angketMinat'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $angketMinat = AngketMinat::findOrFail($id);
        return view('data_penelitian.angket_minat.edit', compact('angketMinat'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            
            'nilai_1' => 'required|numeric|min:1|max:5',
            'nilai_2' => 'required|numeric|min:1|max:5',
            'nilai_3' => 'required|numeric|min:1|max:5',
            'nilai_4' => 'required|numeric|min:1|max:5',
            'nilai_5' => 'required|numeric|min:1|max:5',
            'nilai_6' => 'required|numeric|min:1|max:5',
            'nilai_7' => 'required|numeric|min:1|max:5',
            'nilai_8' => 'required|numeric|min:1|max:5',
            'nilai_9' => 'required|numeric|min:1|max:5',
            'nilai_10' => 'required|numeric|min:1|max:5',
            'nilai_11' => 'required|numeric|min:1|max:5',
            'nilai_12' => 'required|numeric|min:1|max:5',
            'nilai_13' => 'required|numeric|min:1|max:5',
            'nilai_14' => 'required|numeric|min:1|max:5',
        ]);

        $angketMinat = AngketMinat::findOrFail($id);

        // Hitung total nilai
        $totalNilai = $request->nilai_1 + $request->nilai_2 + $request->nilai_3 +
            $request->nilai_4 + $request->nilai_5 + $request->nilai_6 +
            $request->nilai_7 + $request->nilai_8 + $request->nilai_9 +
            $request->nilai_10 + $request->nilai_11 + $request->nilai_12 +
            $request->nilai_13 + $request->nilai_14;

        $angketMinat->update([
            
            'nilai_1' => $request->nilai_1,
            'nilai_2' => $request->nilai_2,
            'nilai_3' => $request->nilai_3,
            'nilai_4' => $request->nilai_4,
            'nilai_5' => $request->nilai_5,
            'nilai_6' => $request->nilai_6,
            'nilai_7' => $request->nilai_7,
            'nilai_8' => $request->nilai_8,
            'nilai_9' => $request->nilai_9,
            'nilai_10' => $request->nilai_10,
            'nilai_11' => $request->nilai_11,
            'nilai_12' => $request->nilai_12,
            'nilai_13' => $request->nilai_13,
            'nilai_14' => $request->nilai_14,
            'total_nilai' => $totalNilai,
        ]);

        return redirect()->route('angket-minat.index')->with('success', 'Data angket minat berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $angketMinat = AngketMinat::findOrFail($id);
        $angketMinat->delete();

        return redirect()->route('angket-minat.index')->with('success', 'Data angket minat berhasil dihapus!');
    }

    /**
     * Import data from Excel file.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            Excel::import(new AngketMinatImport, $request->file('file'));

            return redirect()->route('angket-minat.index')->with('success', 'Data berhasil diimport dari file Excel!');
        } catch (\Exception $e) {
            return redirect()->route('angket-minat.index')->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
        }
    }

    /**
     * Show import form.
     */
    public function importForm()
    {
        return view('angket-minat.import');
    }

    /**
     * Export data to Excel.
     */


    /**
     * Hapus semua data angket minat
     */
    public function clear(Request $request)
    {
        AngketMinat::query()->delete();
        return redirect()->route('angket-minat.index')->with('success', 'Semua data angket minat berhasil dihapus!');
    }
}
