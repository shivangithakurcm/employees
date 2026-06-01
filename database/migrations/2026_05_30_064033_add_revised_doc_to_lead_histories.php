<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lead_histories', function (Blueprint $table) {
            // Revised proposal document path
            $table->string('revised_document')->nullable()->after('document');

            // Negotiation amount — shown in history timeline
            $table->decimal('negotiation_amount', 12, 2)->nullable()->after('revised_document');
        });
    }

    public function down(): void
    {
        Schema::table('lead_histories', function (Blueprint $table) {
            $table->dropColumn(['revised_document', 'negotiation_amount']);
        });
    }
};