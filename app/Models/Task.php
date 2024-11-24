<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'due_date', 'author_id', 'executor_id', 'status'
    ];

    public function author(){
        return $this->belongsTo(User::class, 'author_id');
    }

    public function executor(){
        return $this->belongsTo(User::class, 'executor_id');
    }

    public function discussions()
    {
        return $this->hasMany(Discussion::class);
    }

}
