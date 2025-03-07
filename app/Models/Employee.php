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

    public function records(){
        return $this->hasMany(Record::class);
    }
}
