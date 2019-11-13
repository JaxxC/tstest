<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{

    protected $fillable   = [
        'name'
    ];

    public $timestamps = false;

    public function files()
    {
        return $this->hasMany(FormFile::class);
    }
}
