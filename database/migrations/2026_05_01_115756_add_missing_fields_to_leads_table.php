<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {

            if (!Schema::hasColumn('leads', 'amount')) {
                $table->decimal('amount', 12, 2)->nullable();
            }

            if (!Schema::hasColumn('leads', 'timeline')) {
                $table->string('timeline')->nullable();
            }

            if (!Schema::hasColumn('leads', 'proposal_document')) {
                $table->string('proposal_document')->nullable();
            }

            if (!Schema::hasColumn('leads', 'negotiation_amount')) {
                $table->decimal('negotiation_amount', 12, 2)->nullable();
            }

            if (!Schema::hasColumn('leads', 'revised_proposal')) {
                $table->string('revised_proposal')->nullable();
            }

        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(array_filter([
                Schema::hasColumn('leads', 'amount') ? 'amount' : null,
                Schema::hasColumn('leads', 'timeline') ? 'timeline' : null,
                Schema::hasColumn('leads', 'proposal_document') ? 'proposal_document' : null,
                Schema::hasColumn('leads', 'negotiation_amount') ? 'negotiation_amount' : null,
                Schema::hasColumn('leads', 'revised_proposal') ? 'revised_proposal' : null,
            ]));
        });
    }
};