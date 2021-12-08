<?php

namespace Modules\Telegram\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;
    protected $fillable = ['full_name', 'birth_date', 'phone_number', 'department', 'region', 'live_in_tashkent', 'study_degree','nationality',
                            'family_position', 'previous_company_position', 'salary_expectation', 'time_limit', 'business_trip',
                            'military_service', 'judgment', 'driver_license', 'has_auto', 'russian_level', 'english_level',
                            'chinese_level', 'other_language_level','word_level', 'excel_level', '1c_level', 'other_program_level',
                            'where_know', 'image', 'status'];
    
}