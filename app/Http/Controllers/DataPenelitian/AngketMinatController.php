<?php

namespace App\Http\Controllers\DataPenelitian;

use App\Http\Controllers\Controller;
use App\Imports\AngketMinatImport;
use App\Models\AngketMinat;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class AngketMinatController extends Controller
{
    public function index()
    {
        $dataAngket = AngketMinat::with(['siswa', 'kelas'])->get();
        return view('data_penelitian.angket_minat.index', compact('dataAngket'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $siswas = Siswa::all();
        $kelas = Kelas::all();
        return view('data_penelitian.angket_minat.create', compact('siswas', 'kelas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Handle file import
        if ($request->hasFile('data_minat')) {
            $request->validate([
                'data_minat' => 'required|mimes:xlsx,xls,csv|max:2048'
            ]);

            try {
                $file = $request->file('data_minat');
                $filePath = $file->store('temp');
                
                Excel::import(new AngketMinatImport($filePath), $file);
                
                Storage::delete($filePath);

                return redirect()->route('angket-minat.index')->with('success', 'Data berhasil diimport dari file Excel!');
            } catch (\Exception $e) {
                return redirect()->route('angket-minat.index')->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
            }
        }

        // Handle manual form submission
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'kelas_id' => 'required|exists:kelas,id',
            'pertanyaan_1' => 'required|numeric|min:1|max:5',
            'pertanyaan_2' => 'required|numeric|min:1|max:5',
            'pertanyaan_3' => 'required|numeric|min:1|max:5',
            'pertanyaan_4' => 'required|numeric|min:1|max:5',
            'pertanyaan_5' => 'required|numeric|min:1|max:5',
            'pertanyaan_6' => 'required|numeric|min:1|max:5',
            'pertanyaan_7' => 'required|numeric|min:1|max:5',
            'pertanyaan_8' => 'required|numeric|min:1|max:5',
            'pertanyaan_9' => 'required|numeric|min:1|max:5',
            'pertanyaan_10' => 'required|numeric|min:1|max:5',
            'pertanyaan_11' => 'required|numeric|min:1|max:5',
            'pertanyaan_12' => 'required|numeric|min:1|max:5',
            'pertanyaan_13' => 'required|numeric|min:1|max:5',
            'pertanyaan_14' => 'required|numeric|min:1|max:5',
        ]);

        // Hitung total nilai
        $totalNilai = $request->pertanyaan_1 + $request->pertanyaan_2 + $request->pertanyaan_3 +
            $request->pertanyaan_4 + $request->pertanyaan_5 + $request->pertanyaan_6 +
            $request->pertanyaan_7 + $request->pertanyaan_8 + $request->pertanyaan_9 +
            $request->pertanyaan_10 + $request->pertanyaan_11 + $request->pertanyaan_12 +
            $request->pertanyaan_13 + $request->pertanyaan_14;

        AngketMinat::create([
            'siswa_id' => $request->siswa_id,
            'kelas_id' => $request->kelas_id,
            'pertanyaan_1' => $request->pertanyaan_1,
            'pertanyaan_2' => $request->pertanyaan_2,
            'pertanyaan_3' => $request->pertanyaan_3,
            'pertanyaan_4' => $request->pertanyaan_4,
            'pertanyaan_5' => $request->pertanyaan_5,
            'pertanyaan_6' => $request->pertanyaan_6,
            'pertanyaan_7' => $request->pertanyaan_7,
            'pertanyaan_8' => $request->pertanyaan_8,
            'pertanyaan_9' => $request->pertanyaan_9,
            'pertanyaan_10' => $request->pertanyaan_10,
            'pertanyaan_11' => $request->pertanyaan_11,
            'pertanyaan_12' => $request->pertanyaan_12,
            'pertanyaan_13' => $request->pertanyaan_13,
            'pertanyaan_14' => $request->pertanyaan_14,
            'total' => $totalNilai,
        ]);

        return redirect()->route('angket-minat.index')->with('success', 'Data angket minat berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $angketMinat = AngketMinat::with(['siswa', 'kelas'])->findOrFail($id);
        return view('data_penelitian.angket_minat.show', compact('angketMinat'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $angketMinat = AngketMinat::findOrFail($id);
        $siswas = Siswa::all();
        $kelas = Kelas::all();
        return view('data_penelitian.angket_minat.edit', compact('angketMinat', 'siswas', 'kelas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'kelas_id' => 'required|exists:kelas,id',
            'pertanyaan_1' => 'required|numeric|min:1|max:5',
            'pertanyaan_2' => 'required|numeric|min:1|max:5',
            'pertanyaan_3' => 'required|numeric|min:1|max:5',
            'pertanyaan_4' => 'required|numeric|min:1|max:5',
            'pertanyaan_5' => 'required|numeric|min:1|max:5',
            'pertanyaan_6' => 'required|numeric|min:1|max:5',
            'pertanyaan_7' => 'required|numeric|min:1|max:5',
            'pertanyaan_8' => 'required|numeric|min:1|max:5',
            'pertanyaan_9' => 'required|numeric|min:1|max:5',
            'pertanyaan_10' => 'required|numeric|min:1|max:5',
            'pertanyaan_11' => 'required|numeric|min:1|max:5',
            'pertanyaan_12' => 'required|numeric|min:1|max:5',
            'pertanyaan_13' => 'required|numeric|min:1|max:5',
            'pertanyaan_14' => 'required|numeric|min:1|max:5',
        ]);

        $angketMinat = AngketMinat::findOrFail($id);

        // Hitung total nilai
        $totalNilai = $request->pertanyaan_1 + $request->pertanyaan_2 + $request->pertanyaan_3 +
            $request->pertanyaan_4 + $request->pertanyaan_5 + $request->pertanyaan_6 +
            $request->pertanyaan_7 + $request->pertanyaan_8 + $request->pertanyaan_9 +
            $request->pertanyaan_10 + $request->pertanyaan_11 + $request->pertanyaan_12 +
            $request->pertanyaan_13 + $request->pertanyaan_14;

        $angketMinat->update([
            'siswa_id' => $request->siswa_id,
            'kelas_id' => $request->kelas_id,
            'pertanyaan_1' => $request->pertanyaan_1,
            'pertanyaan_2' => $request->pertanyaan_2,
            'pertanyaan_3' => $request->pertanyaan_3,
            'pertanyaan_4' => $request->pertanyaan_4,
            'pertanyaan_5' => $request->pertanyaan_5,
            'pertanyaan_6' => $request->pertanyaan_6,
            'pertanyaan_7' => $request->pertanyaan_7,
            'pertanyaan_8' => $request->pertanyaan_8,
            'pertanyaan_9' => $request->pertanyaan_9,
            'pertanyaan_10' => $request->pertanyaan_10,
            'pertanyaan_11' => $request->pertanyaan_11,
            'pertanyaan_12' => $request->pertanyaan_12,
            'pertanyaan_13' => $request->pertanyaan_13,
            'pertanyaan_14' => $request->pertanyaan_14,
            'total' => $totalNilai,
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
     * Hapus semua data angket minat
     */
    public function clear(Request $request)
    {
        AngketMinat::query()->delete();
        return redirect()->route('angket-minat.index')->with('success', 'Semua data angket minat berhasil dihapus!');
    }
}