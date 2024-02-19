<?php

namespace App\Listeners;

use App\Events\OrganizationCreated;
use App\Models\Book;
use App\Models\OrganizationBook;
use App\Models\OrganizationBookStatus;
use App\Models\ReceivedBook;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationBookInventoryTable implements ShouldQueue
{

    /**
     * Handle the event.
     */
    public function handle(OrganizationCreated $event): void
    {
        $organizationId = $event->organizationId;
        $tableName = 'organization_book_inventories_' . $organizationId;
        if (!Schema::hasTable($tableName)) {
            Schema::create($tableName, function (Blueprint $table) {
                $table->id();

                $table->foreignIdFor(Book::class)
                    ->constrained();

                $table->foreignIdFor(ReceivedBook::class);

                $table->decimal('price', 10, 2)
                    ->nullable();

                $table->unsignedBigInteger('num')
                    ->nullable()
                    ->index('num_index');

                $table->unsignedBigInteger('subnum')
                    ->nullable()
                    ->index('subnum_index');

                $table->string('code')
                    ->nullable();

                $table->foreignId('transaction_id')
                    ->nullable();

                $table->foreignIdFor(OrganizationBookStatus::class)
                    ->default(1);

                $table->timestamps();
            });
        }
    }
}
