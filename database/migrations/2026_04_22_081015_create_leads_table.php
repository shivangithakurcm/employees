<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('contact_number');
            $table->string('state');
            $table->string('email')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->text('Requirement')->nullable();
            $table->date('date')->nullable();
            $table->time('time')->nullable();
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
            ])->default('call_back_received');
            $table->enum('discussion', ['draft', 'add'])->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};