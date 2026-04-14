<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('employee_previous_employers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->string('company_name');
            $table->string('hr_name')->nullable();
            $table->string('hr_phone')->nullable();
            $table->string('address_line1')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('pincode')->nullable();
            $table->decimal('monthly_salary', 10, 2)->nullable();
            $table->string('designation')->nullable();
            $table->string('duration')->nullable();
            $table->string('salary_slip')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_previous_employers');
    }
};