<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Makul extends Model
{
    protected $table = "mata_kuliah";
    protected $primaryKey = "id_makul";
    public $timestamps = false;

    protected $fillable = [
        'kode_makul', 'mata_kuliah', 'sks'
    ];

}
