<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Monitoring extends Model
{
    protected $fillable = ['tanggal', 'kebun_id', 'jenis_gangguan', 'nama_gangguan', 'keterangan'];

    public function kebun()
    {
        return $this->belongsTo(Kebun::class);
    }
}