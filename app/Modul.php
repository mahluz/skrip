<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Modul extends Model
{
    protected $table = "modul_pembelajaran";
    protected $primaryKey = "id_modul";
    public $timestamps = false;

    protected $fillable = [
        'no_id', 'nama_modul', 'id_makul', 'modul'
    ];

}
