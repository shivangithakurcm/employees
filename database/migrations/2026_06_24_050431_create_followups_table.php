<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('followups', function (Blueprint $table) {
        $table->id();
        $table->foreignId('lead_id')->constrained('leads')->onDelete('cascade');
        $table->foreignId('assigned_to')->constrained('users')->onDelete('cascade');
        $table->datetime('followup_date');
        $table->text('notes')->nullable();
        $table->enum('status', ['pending', 'done', 'overdue'])->default('pending');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('followups');
    }
};
