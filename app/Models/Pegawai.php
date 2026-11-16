<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pegawai extends Model
{
    protected $table = 'pegawai';

    protected $fillable = [
        'user_id',
        'kode_pegawai',
        'nama_lengkap',
        'jenis_kelamin',
        'umur',
        'pendidikan_terakhir',
        'jabatan',
        'alamat',
    ];

    public static function generateKodePegawai(): string
    {
        $lastPegawai = self::orderBy('id', 'desc')->first();

        if (!$lastPegawai) {
            return 'EMP001';
        }

        $lastNumber = (int) preg_replace('/[^0-9]/', '', $lastPegawai->kode_pegawai);
        $nextNumber = $lastNumber + 1;

        return 'EMP' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function absensi(): HasMany
    {
        return $this->hasMany(Absensi::class);
    }

    public function cuti(): HasMany
    {
        return $this->hasMany(Cuti::class);
    }

    public function slipGaji(): HasMany
    {
        return $this->hasMany(SlipGaji::class);
    }

    public function tugasBulanan(): HasMany
    {
        return $this->hasMany(TugasBulanan::class);
    }
    
    public function klaimStruks(): HasMany
    {
        return $this->hasMany(KlaimStruk::class);
    }
}