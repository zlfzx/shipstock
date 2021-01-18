<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemakaian extends Model
{
    use HasFactory;

    protected $table = 'pemakaian';

    protected $fillable = ['sparepart_id', 'kapal_id', 'jumlah', 'tanggal_pemakaian'];

    public function sparepart() {
        return $this->belongsTo(Sparepart::class,  'sparepart_id')->with('warehouse');
    }

    public function kapal() {
        return $this->belongsTo(Kapal::class, 'kapal_id');
    }
}
