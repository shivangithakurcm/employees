<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('employee_official_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->date('doj')->nullable();
            $table->string('designation')->nullable();
            $table->string('shift_name')->nullable();
$table->time('shift_from')->nullable();
$table->time('shift_to')->nullable();
            $table->decimal('salary', 10, 2)->nullable();
            $table->string('branch')->nullable();
            $table->string('permission')->nullable();
            $table->string('password')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_official_details');
    }
};