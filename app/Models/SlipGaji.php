<?php

// app/Models/SlipGaji.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SlipGaji extends Model
{
    protected $table = 'slip_gaji';
    protected $fillable = ['pegawai_id', 'periode_bulan', 'gaji_pokok', 'tunjangan', 'potongan', 'total_gaji'];

    // Enkapsulasi kalkulasi total gaji bersih otomatis sebelum disimpan
    protected static function booted(): void
    {
        static::saving(function (SlipGaji $slip) {
            $slip->total_gaji = ($slip->gaji_pokok + $slip->tunjangan) - $slip->potongan;
        });
    }

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }
}