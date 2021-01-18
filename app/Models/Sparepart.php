<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sparepart extends Model
{
    use HasFactory;

    protected $table = 'sparepart';

    protected $fillable = ['kode', 'nama', 'stok', 'warehouse_id'];

    public function warehouse() {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function pemakaian() {
        return $this->hasMany(Pemakaian::class, 'sparepart_id');
    }
}
