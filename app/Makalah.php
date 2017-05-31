<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Makalah extends Model
{
    protected $table = "makalah";
    protected $primaryKey = "id_makalah";

    protected $fillable = [
	    'judul',
	    'abstrak',
	    'permasalahan',
	    'tujuan',
	    'tinjauan_pustaka',
	    'kesimpulan_sementara',
	    'daftar_pustaka',
	    'id_user',
	    'id_status',
      'dosen_id'
    ];
}
