<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPhotoToEmployeeBankDetailsTable extends Migration
{
    public function up(): void
    {
        Schema::table('employee_bank_details', function (Blueprint $table) {
            $table->string('photo')->nullable()->after('ifsc_code');
        });
    }

    public function down(): void
    {
        Schema::table('employee_bank_details', function (Blueprint $table) {
            $table->dropColumn('photo');
        });
    }
}