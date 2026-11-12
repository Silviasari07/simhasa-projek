<?php

namespace App\Http\Controllers;

use App\Models\Hama;
use Illuminate\Http\Request;

class HamaController extends Controller
{
    public function index()
    {
        $hama = Hama::latest()->get();
        return view('hama.index', compact('hama'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_hama' => 'required',
            'jenis_tanaman' => 'required',
            'gejala' => 'required',
            'penanganan' => 'required',
        ]);

        Hama::create($request->all());

        return redirect()->back()->with('success', 'Data hama berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_hama' => 'required',
            'jenis_tanaman' => 'required',
            'gejala' => 'required',
            'penanganan' => 'required',
        ]);

        $hama = Hama::findOrFail($id);
        $hama->update($request->all());

        return redirect()->back()->with('success', 'Data hama berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $hama = Hama::findOrFail($id);
        $hama->delete();

        return redirect()->back()->with('success', 'Data hama berhasil dihapus!');
    }
}