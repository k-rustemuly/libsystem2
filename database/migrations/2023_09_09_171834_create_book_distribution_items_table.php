<?php

use App\Models\Book;
use App\Models\BookDistributionTemplate;
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
        Schema::create('book_distribution_items', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(BookDistributionTemplate::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignIdFor(Book::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->unsignedInteger('count')
                ->default(1);

            $table->unique([
                'book_distribution_template_id',
                'book_id'
            ], 'template_book_uniq');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_distribution_items');
    }
};
