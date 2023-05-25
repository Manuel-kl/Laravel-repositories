<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class Task extends Model
{
    use HasFactory, SoftDeletes, HasApiTokens;
    protected $fillable = [
        'title',
        'description',
        'status',
        'user_id'
    ];
    protected $table = "tasks";

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}