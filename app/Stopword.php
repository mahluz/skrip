<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stopword extends Model
{
    protected $table = "stoplist";
    protected $primaryKey = "kata";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'kata'
    ];
}
