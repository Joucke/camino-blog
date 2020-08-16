<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Photo extends Model
{
    protected $guarded = [];

    public function delete()
    {
        Storage::disk('photos')->delete($this->path);
        parent::delete();
    }
}
