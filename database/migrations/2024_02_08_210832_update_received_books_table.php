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
        Schema::table('received_books', function (Blueprint $table) {
            if (!Schema::hasColumn('received_books', 'price')) {
                $table->decimal('price', 10, 2)
                    ->nullable()
                    ->after('year');
            }
            if (!Schema::hasColumn('received_books', 'total')) {
                $table->decimal('total', 10, 2)
                    ->nullable()
                    ->after('count');
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
