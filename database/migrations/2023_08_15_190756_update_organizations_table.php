<?php

use App\Models\OrganizationType;
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
            if (!Schema::hasColumn('organizations', 'kato')) {
                $table->bigInteger('kato')
                    ->after('bin')
                    ->default(471010000);
            }
            if (!Schema::hasColumn('organizations', 'organization_type_id')) {
                $table->foreignIdFor(OrganizationType::class)
                    ->after('kato')
                    ->default(1)
                    ->constrained()
                    ->cascadeOnDelete()
                    ->cascadeOnUpdate();
            }
            if (!Schema::hasColumn('organizations', 'legal_address_kk')) {
                $table->string('legal_address_kk')
                    ->nullable();
            }
            if (!Schema::hasColumn('organizations', 'legal_address_ru')) {
                $table->string('legal_address_ru')
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
