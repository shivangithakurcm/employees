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
    Schema::table('leads', function (Blueprint $table) {

        // Client Details
        $table->string('won_name')->nullable();
        $table->string('won_contact')->nullable();
        $table->string('won_email')->nullable();
        $table->string('won_designation')->nullable();
        $table->string('won_business_name')->nullable();
        $table->string('won_gst_no')->nullable();
        $table->string('won_location')->nullable();

        $table->string('won_country')->nullable();
        $table->string('won_state')->nullable();
        $table->string('won_city')->nullable();

        // Project Details
        $table->text('won_project_detail')->nullable();
        $table->decimal('won_final_cost', 12, 2)->nullable();
        $table->string('won_milestone')->nullable();
        $table->string('won_timeline')->nullable();

        // Token Fields
        $table->string('won_token_received')->nullable();
        $table->decimal('won_token_amount', 12, 2)->nullable();
        $table->string('won_amount_type')->nullable();
        $table->date('won_received_date')->nullable();
        $table->string('won_gst_type')->nullable();

    });
}
public function down(): void
{
    Schema::table('leads', function (Blueprint $table) {
        $table->dropColumn([
            'won_name',
            'won_contact',
            'won_email',
            'won_designation',
            'won_business_name',
            'won_gst_no',
            'won_location',
            'won_country',
            'won_state',
            'won_city',
            'won_project_detail',
            'won_final_cost',
            'won_milestone',
            'won_timeline',
            'won_token_received',
            'won_token_amount',
            'won_amount_type',
            'won_received_date',
            'won_gst_type'
        ]);
    });
}
};
