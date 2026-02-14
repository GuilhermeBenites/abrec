<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();

            // Personal Data (Required)
            $table->string('name');
            $table->string('cpf', 11)->unique();
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female']);
            $table->string('address');
            $table->string('neighborhood');
            $table->string('city');

            // Health Information (Optional - all nullable)
            $table->decimal('weight', 5, 2)->nullable();
            $table->unsignedSmallInteger('height')->nullable();
            $table->string('blood_pressure', 10)->nullable();
            $table->unsignedSmallInteger('blood_glucose')->nullable();
            $table->string('creatinine', 10)->nullable();

            // Health Conditions (Boolean flags - default false)
            $table->boolean('is_diabetic')->default(false);
            $table->boolean('is_hypertensive')->default(false);
            $table->boolean('has_kidney_problem')->default(false);
            $table->boolean('has_family_drc')->default(false);
            $table->boolean('is_obese')->default(false);
            $table->boolean('has_back_eye_exam')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
