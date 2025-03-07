<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'category_name',
        'category_description',
        'status',
    ];

    public function subCategories()
    {
        return $this->hasMany(SubCategory::class);
    }

    public function records()
    {
        return $this->hasMany(Record::class);
    }
}
