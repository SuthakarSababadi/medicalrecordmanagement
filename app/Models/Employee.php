<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'name',
        'email',
        'ic_no',
        'phone_no',
        'address',
        'citizenship',
        'position',
        'worker_no',
        'status',
    ];

    public function records()
    {
        return $this->belongsToMany(Record::class);
    }

    public function appointments()
    {
        return $this->belongsToMany(Appointment::class);
    }
}
