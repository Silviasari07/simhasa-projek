<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kebun extends Model
{
    protected $fillable = ['nama_kebun', 'pemilik', 'lokasi', 'luas_lahan'];

    public function monitorings()
    {
        return $this->hasMany(Monitoring::class);
    }
}