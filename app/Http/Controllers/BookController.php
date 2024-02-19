<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\OrganizationBookInventory;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function search(Request $request)
    {
        $search = $request['query'];
        return Book::search($search)->get()->map(function ($item) {
            return [
                'value' => $item->id,
                'label' => $item->name. " ($item->isbn)",
            ];
        })->all();
    }

    public function searchByInventory(Request $request)
    {
        $search = $request['query'];
        $inventories = new OrganizationBookInventory(['organization_id' => session('selected_admin')?->organization_id]);
        return $inventories->where('code', 'like', "%{$search}%")
            ->whereNull('transaction_id')
            ->with('book')
            ->get()
            ->map(function ($item) {
                return [
                    'value' => $item->id,
                    'label' => $item->code. " (".$item->book->name.")",
                ];
            })->all();
    }
}
