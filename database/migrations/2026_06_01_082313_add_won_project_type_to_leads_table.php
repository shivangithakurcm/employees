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
        $table->unsignedBigInteger('won_project_type')->nullable()->after('won_project_detail');
    });
}

public function down(): void
{
    Schema::table('leads', function (Blueprint $table) {
        $table->dropColumn('won_project_type');
    });
}
};
