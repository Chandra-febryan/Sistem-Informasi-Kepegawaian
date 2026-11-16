<?php

// app/Models/TugasBulanan.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TugasBulanan extends Model
{
    protected $table = 'tugas_bulanan';
    protected $fillable = ['pegawai_id', 'judul_tugas', 'deskripsi', 'bulan_target', 'status'];

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }
}