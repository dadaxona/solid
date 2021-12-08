<?php

namespace Modules\EducationFinance\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Education\Entities\Group;
use Modules\Education\Entities\Student;

class EducationTransaction extends Model
{
    use HasFactory;
    protected $fillable = ['performed_at', 'group_id', 'student_id', 'amount', 'description'];
    public function group()
    {
        return $this->belongsTo(Group::class);
    }
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}