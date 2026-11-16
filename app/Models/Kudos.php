<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kudos extends Model
{
    protected $table = 'kudos';

    protected $fillable = ['pengirim_id', 'penerima_id', 'pesan'];

    public function pengirim(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'pengirim_id');
    }

    public function penerima(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'penerima_id');
    }
}