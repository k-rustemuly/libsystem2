<?php

namespace App\Listeners;

use App\Events\OrganizationCreated;
use App\Models\Book;
use App\Models\OrganizationBook;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationBookTransactionTable implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(OrganizationCreated $event): void
    {
        $tableName = 'organization_book_transactions_' . $event->organizationId;
        if (!Schema::hasTable($tableName)) {
            Schema::create($tableName, function (Blueprint $table) {
                $table->id();

                $table->foreignIdFor(Book::class)
                    ->constrained();

                $table->morphs('recipientable', 'recipientable_index');

                $table->foreignId('inventory_id')->nullable();

                $table->date('received_date');

                $table->date('return_date');

                $table->date('returned_date')->nullable();

                $table->string('comment', 255)->nullable();

                $table->timestamps();
            });
        }
    }
}
