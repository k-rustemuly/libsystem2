<?php

namespace App\Http\Controllers;

use App\Models\Book;
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
}
