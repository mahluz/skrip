<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    protected $table = "tugas";
    protected $primaryKey = "id_tugas";
    public $timestamps = false;

    protected $fillable = [
        'id_user' ,'no_id', 'nama_tugas', 'id_makul', 'tugas', 'create_at'
    ];

}
