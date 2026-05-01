<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->decimal('amount', 12, 2)->nullable();
            $table->string('timeline')->nullable();
            $table->string('proposal_document')->nullable();
            $table->decimal('negotiation_amount', 12, 2)->nullable();
            $table->string('revised_proposal')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn([
                'amount',
                'timeline',
                'proposal_document',
                'negotiation_amount',
                'revised_proposal'
            ]);
        });
    }
};