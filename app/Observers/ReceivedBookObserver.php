<?php

namespace App\Observers;

use App\Models\OrganizationBook;
use App\Models\OrganizationBookInventory;
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
        (new OrganizationBookInventory(['organization_id' => $receivedBook->organization_id]))->generate($receivedBook, (int) $receivedBook->count, $receivedBook->price);
    }

    public function updated(ReceivedBook $receivedBook)
    {
        $original = $receivedBook->getOriginal();
        if($original["count"] > $receivedBook->count) {
            $organizationBook = OrganizationBook::where('organization_id', $receivedBook->organization_id)->where('book_id', $receivedBook->book_id)->first();
            $organizationBook->count-=1;
            $organizationBook->save();

            if($receivedBook->price) {
                $receivedBook->total = $receivedBook->price * $receivedBook->count;
            }
        }
        if($receivedBook->count == 0) {
            $receivedBook->delete();
        }
    }


}
