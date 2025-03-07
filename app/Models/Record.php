<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    protected $fillable = [
        'title',
        'subcategory_id',
        'category_id',
        'user_id',
        'attachment',
        'description',

    ];

    protected $casts = [
        'attachment' => 'array', // This will cast the attachments field as an array when retrieved
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
