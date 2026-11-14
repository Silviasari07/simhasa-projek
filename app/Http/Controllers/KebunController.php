<?php

namespace App\Http\Controllers;

use App\Models\Kebun;
use Illuminate\Http\Request;

class KebunController extends Controller
{
    public function index()
    {
        $kebuns = Kebun::latest()->get();
        return view('kebun.index', compact('kebuns'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kebun' => 'required',
            'pemilik' => 'required',
            'lokasi' => 'required',
            'luas_lahan' => 'required',
        ]);

       Kebun::create($request->except('_token'));

        return redirect()->back()->with('success', 'Data kebun berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kebun' => 'required',
            'pemilik' => 'required',
            'lokasi' => 'required',
            'luas_lahan' => 'required',
        ]);

        $kebun = Kebun::findOrFail($id);
        $kebun->update($request->all());

        return redirect()->back()->with('success', 'Data kebun berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $kebun = Kebun::findOrFail($id);
        $kebun->delete();

        return redirect()->back()->with('success', 'Data kebun berhasil dihapus!');
    }
}