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
        Schema::table('organizations', function (Blueprint $table) {
            if (!Schema::hasColumn('organizations', 'short_name_kk')) {
                $table->string('short_name_kk')
                    ->nullable();
            }
            if (!Schema::hasColumn('organizations', 'short_name_ru')) {
                $table->string('short_name_ru')
                    ->nullable();
            }
            if (!Schema::hasColumn('organizations', 'short_type_kk')) {
                $table->string('short_type_kk')
                    ->nullable();
            }
            if (!Schema::hasColumn('organizations', 'short_type_ru')) {
                $table->string('short_type_ru')
                    ->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
