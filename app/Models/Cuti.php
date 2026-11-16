<?php

// app/Models/Cuti.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cuti extends Model
{
    protected $table = 'cuti';
    protected $fillable = ['pegawai_id', 'tanggal_mulai', 'tanggal_selesai', 'keterangan', 'status'];

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }
}