<?php

namespace Modules\Education\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    protected $fillable = ['cours_id', 'group_id', 'date_for'];
    
    public function students()
    {
        return $this->belongsToMany(Student::class);
    }
    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}