<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{

    protected $fillable = [
        'title',
        'sub_category_id',
        'user_id',
        'attachment',
        'description',
    ];
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
