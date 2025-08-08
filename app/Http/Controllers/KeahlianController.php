<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Keahlian;

class KeahlianController extends Controller
{
    public function index()
    {
        $keahlian = Keahlian::all();
        return view('keahlian.index', compact('keahlian'));
    }

    public function create()
    {
        return view('keahlian.add');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        Keahlian::create($validated);
        return redirect()->route('keahlian.index')->with('success', 'Keahlian berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $keahlian = Keahlian::findOrFail($id);
        return view('keahlian.edit', compact('keahlian'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $keahlian = Keahlian::findOrFail($id);
        $keahlian->update($validated);
        return redirect()->route('keahlian.index')->with('success', 'Keahlian berhasil diupdate!');
    }
}
