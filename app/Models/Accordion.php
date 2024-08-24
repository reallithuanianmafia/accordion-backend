<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accordion extends Model
{
    protected $fillable = ['title', 'content', 'parent_id'];

    public function children()
    {
        return $this->hasMany(Accordion::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Accordion::class, 'parent_id');
    }
}