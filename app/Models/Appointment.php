<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    protected $fillable = [
        'name',
        'employee_id',
        'date',
        'remarks',
        'category_id',
    ];

    public function setDateAttribute($value)
    {
        // Convert the date from 'd/m/Y' to 'Y-m-d' before saving
        $this->attributes['date'] = Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');

    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
