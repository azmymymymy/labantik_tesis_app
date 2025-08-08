<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Konsentrasi;

class KonsentrasiController extends Controller
{
    public function index()
    {
        $konsentrasi = Konsentrasi::all();
        return view('konsentrasi.index', compact('konsentrasi'));
    }

    public function create()
    {
        return view('konsentrasi.add');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        Konsentrasi::create($validated);
        return redirect()->route('konsentrasi.index')->with('success', 'Konsentrasi berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $konsentrasi = Konsentrasi::findOrFail($id);
        return view('konsentrasi.edit', compact('konsentrasi'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $konsentrasi = Konsentrasi::findOrFail($id);
        $konsentrasi->update($validated);
        return redirect()->route('konsentrasi.index')->with('success', 'Konsentrasi berhasil diupdate!');
    }
}
