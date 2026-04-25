<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('follow_ups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained('leads')->onDelete('cascade');
            $table->text('comment')->nullable();
            $table->enum('status', [
                'call_back_received',
                'call_schedule',
                'not_interested',
                'not_responded',
                'not_in_scope',
                'follow_up',
                'qualified',
                'proposal_sent',
                'lost',
                'won'
            ])->default('follow_up');
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('follow_ups');
    }
};