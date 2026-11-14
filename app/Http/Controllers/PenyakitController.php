<?php

namespace App\Http\Controllers;

use App\Models\Penyakit;
use Illuminate\Http\Request;

class PenyakitController extends Controller
{
    public function index()
    {
        $penyakits = Penyakit::latest()->get();
        return view('penyakit.index', compact('penyakits'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_penyakit' => 'required',
            'gejala' => 'required',
            'penanganan' => 'required',
        ]);

    Penyakit::create($request->except('_token'));

        return redirect()->back()->with('success', 'Data penyakit berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_penyakit' => 'required',
            'gejala' => 'required',
            'penanganan' => 'required',
        ]);

        $penyakit = Penyakit::findOrFail($id);
        $penyakit->update($request->all());

        return redirect()->back()->with('success', 'Data penyakit berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $penyakit = Penyakit::findOrFail($id);
        $penyakit->delete();

        return redirect()->back()->with('success', 'Data penyakit berhasil dihapus!');
    }
}