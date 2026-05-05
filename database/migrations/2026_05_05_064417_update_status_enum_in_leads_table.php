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
    DB::statement("ALTER TABLE leads MODIFY COLUMN status ENUM(
        'new',
        'contacted',
        'qualified',
        'proposal_sent',
        'not_interested',
        'lost',
        'call_back_required',
        'not_responded',
        'won',
        'call_schedule',
        'negotiation'
    ) NOT NULL DEFAULT 'new'");
}

public function down(): void
{
    DB::statement("ALTER TABLE leads MODIFY COLUMN status ENUM(
        'new',
        'contacted',
        'qualified',
        'proposal_sent',
        'not_interested',
        'lost',
        'call_back_required',
        'not_responded',
        'won',
        'call_schedule'
    ) NOT NULL DEFAULT 'new'");
}
};
