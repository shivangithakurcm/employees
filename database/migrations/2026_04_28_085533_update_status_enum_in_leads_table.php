<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE leads MODIFY COLUMN status ENUM(
            'call_back_required',
            'call_schedule',
            'not_responded',
            'not_interested',
            'not_in_scope',
            'qualified',
            'proposal_sent',
            'won',
            'lost',
            'draft',
            'reschedule'
        ) NULL");
    }

    public function down()
    {
        DB::statement("ALTER TABLE leads MODIFY COLUMN status VARCHAR(100) NULL");
    }
};