<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_applications', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('full_name');
            $table->string('birth_date')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('department')->nullable();
            $table->string('region')->nullable();
            $table->string('live_in_tashkent')->nullable();
            $table->string('study_degree')->nullable();
            $table->string('family_position')->nullable();
            $table->string('previous_company_position')->nullable();
            $table->string('salary_expectation')->nullable();
            $table->string('time_limit')->nullable();
            $table->string('business_trip')->nullable();
            $table->string('military_service')->nullable();
            $table->string('judgment')->nullable();
            $table->string('driver_license')->nullable();
            $table->string('has_auto')->nullable();
            $table->string('russian_level')->nullable();
            $table->string('english_level')->nullable();
            $table->string('chinese_level')->nullable();
            $table->string('other_language_level')->nullable();
            $table->string('word_level')->nullable();
            $table->string('excel_level')->nullable();
            $table->string('1c_level')->nullable();
            $table->string('other_program_level')->nullable();
            $table->string('where_know')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
            $table->string('status')->default('draft');
            $table->string('nationality')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_applications');
    }
}
