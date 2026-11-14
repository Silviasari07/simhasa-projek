<?php

namespace App\Http\Controllers;

use App\Models\Monitoring;
use App\Models\Kebun;
use App\Models\Hama;
use App\Models\Penyakit;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function index()
    {
        $monitorings = Monitoring::with('kebun')->latest()->get();
        $kebuns = Kebun::all();
        $hamas = Hama::all();
        $penyakits = Penyakit::all();

        return view('monitoring.index', compact('monitorings', 'kebuns', 'hamas', 'penyakits'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kebun_id' => 'required|exists:kebuns,id',
            'jenis_gangguan' => 'required',
            'nama_gangguan' => 'nullable|string',
            'keterangan' => 'required',
        ]);

       Monitoring::create($request->except('_token'));

        return redirect()->back()->with('success', 'Catatan monitoring berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kebun_id' => 'required|exists:kebuns,id',
            'jenis_gangguan' => 'required',
            'nama_gangguan' => 'nullable|string',
            'keterangan' => 'required',
        ]);

        $monitoring = Monitoring::findOrFail($id);
        $monitoring->update($request->all());

        return redirect()->back()->with('success', 'Catatan monitoring berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $monitoring = Monitoring::findOrFail($id);
        $monitoring->delete();

        return redirect()->back()->with('success', 'Catatan monitoring berhasil dihapus!');
    }
}