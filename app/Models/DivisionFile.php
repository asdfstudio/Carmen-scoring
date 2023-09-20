<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class DivisionFile extends Model
{
    protected $table = "division_files";
    use SoftDeletes;

    protected $dates = ['deleted_at', 'created_at'];

    protected $fillable = ['division_id', 'round_id', 'choir_id','uploaded_by', 'url','mime','name'];

    public function getUrlAttribute($path)
    {
        return ($path) ? Storage::disk('recordings')->url($path) : '';
    }

    public function getNiceDate()
    {
        return date('M. j, Y \a\t h:i:s A (T)', strtotime($this->created_at));
    }
}
