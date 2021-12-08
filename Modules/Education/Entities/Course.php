<?php

namespace Modules\Education\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'price', 'teacher_id'];
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
}   