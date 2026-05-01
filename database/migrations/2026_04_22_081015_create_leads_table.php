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
            $table->string('state')->nullable();
            $table->string('email')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->text('Requirement')->nullable();
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->enum('status', [
                'call_back_required',   // fixed: was call_back_received
                'call_schedule',
                'not_interested',
                'not_responded',
                'not_in_scope',
                'follow_up',
                'qualified',
                'proposal_sent',
                'negotiation',          // new
                'on_hold',              // new
                'lost',
                'won',
                'draft'                 // new
            ])->default('call_back_required');
            $table->enum('discussion', ['draft', 'add'])->nullable();
            $table->text('comment')->nullable();

            // Proposal Sent fields
            $table->decimal('amount', 12, 2)->nullable();           // proposal amount
            $table->string('timeline')->nullable();                 // e.g. "2 weeks"
            $table->string('proposal_document')->nullable();        // file path

            // Negotiation fields
            $table->decimal('negotiation_amount', 12, 2)->nullable();
            $table->string('revised_proposal')->nullable();         // revised doc path

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};