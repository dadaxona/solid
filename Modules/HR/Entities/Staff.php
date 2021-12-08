<?php

namespace Modules\HR\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\User\Entities\User;

class Staff extends Model
{
    use HasFactory;

    protected $fillable = ['came_at','work_book','study_degree','specialization','experience','staffposition_id','user_id'];
    
    public function staffposition()
    {
        return $this->belongsTo(StaffPosition::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
