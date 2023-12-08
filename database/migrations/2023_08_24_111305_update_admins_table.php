<?php

use App\Models\Role;
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
        Schema::table('admins', function (Blueprint $table) {
            $table->foreignIdFor(Role::class)
                ->default(5)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->unique([
                'organization_id',
                'user_id',
                'role_id'
            ]);
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
