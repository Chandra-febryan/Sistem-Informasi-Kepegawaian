<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KlaimStruk extends Model
{
    protected $table = 'klaim_struk';

    protected $fillable = ['pegawai_id', 'kategori', 'keterangan', 'nominal', 'status'];

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }
}