<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormFile extends Model
{
    public $timestamps = false;
    
    protected $fillable   = [
        'form_id', 'name', 'original_name', 'title'
    ];

    /**
     * Get the post that owns the comment.
     */
    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function getPathAttribute()
    {
        return storage_path('app/' . config('uploads.folder') . '/' . $this->name);
    }
}
