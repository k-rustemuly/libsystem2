<?php

namespace App\Observers;

use App\Models\OrganizationBook;
use App\Models\ReceivedBook;

class ReceivedBookObserver
{
    public function creating(ReceivedBook $receivedBook)
    {
        if($receivedBook->price) {
            $receivedBook->total = $receivedBook->price * $receivedBook->count;
        }
    }

    public function created(ReceivedBook $receivedBook) {
        $book = OrganizationBook::firstOrNew(
            [
                'organization_id' => $receivedBook->organization_id,
                'book_id' => $receivedBook->book_id
            ],
            [
                'book_storage_type_id' => $receivedBook->book_storage_type_id,
            ]
        );
        $book->count+=$receivedBook->count;
        $book->save();
    }
}
