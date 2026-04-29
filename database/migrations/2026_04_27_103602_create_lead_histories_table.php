<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lead_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->onDelete('cascade');
            $table->string('event_type')->default('created'); // created | status_changed | edited
            $table->string('from_status')->nullable();
            $table->string('to_status')->nullable();
            $table->string('status')->nullable();
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->string('document')->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lead_histories');
    }
};