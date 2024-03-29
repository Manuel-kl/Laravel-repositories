<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    protected $table = 'statuses';

    public function task()
    {
        return $this->hasMany(Task::class);
    }
}
