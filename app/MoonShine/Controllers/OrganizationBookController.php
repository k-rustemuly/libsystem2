<?php

declare(strict_types=1);

namespace App\MoonShine\Controllers;

use App\Http\Requests\ReceiveBookRequest;
use App\Models\Organization;
use App\Models\OrganizationBookInventory;
use App\Models\OrganizationBookTransaction;
use App\Models\OrganizationReader;
use App\Models\ReceivedBook;
use App\Models\Role;
use App\MoonShine\Resources\OrganizationBookResource;
use MoonShine\MoonShineRequest;
use MoonShine\Http\Controllers\MoonShineController;
use Symfony\Component\HttpFoundation\Response;

final class OrganizationBookController extends MoonShineController
{
    public function store(MoonShineRequest $request): Response
    {
        if(session('selected_admin')?->role_id == Role::LIBRARIAN) {
            collect($request->get('books'))
                ->values()
                ->map(function ($item) use($request) {
                    $item['organization_id'] = session('selected_admin')?->organization_id;
                    $item['book_storage_type_id'] = $request->get('book_storage_type_id');
                    $item['year'] = $request->get('year');
                    ReceivedBook::create($item);
                    return $item;
                });
            $this->toast(__('moonshine::ui.saved'));
        }
        return to_page(resource: new OrganizationBookResource(), redirect: true);
    }

    public function receive(ReceiveBookRequest $request): Response
    {
        $data = $request->validated();
        $dates = $data['date'];
        $reader = OrganizationReader::firstOrCreate(
            [
                'organization_id' => session('selected_admin')?->organization_id,
                'user_id' => $data['user_id']
            ]
        );
        $inventories = new OrganizationBookInventory(['organization_id' => session('selected_admin')?->organization_id]);
        $inventories->whereIn('id', $data['ids'])
            ->get()
            ->each(function($item) use($reader, $dates) {
                $receivedBook = new OrganizationBookTransaction(['organization_id' => session('selected_admin')?->organization_id]);
                // $receivedBook->organizationBook()->associate($item->organization_book_id);
                $receivedBook->book()->associate($item->book_id);
                $receivedBook->recipientable()->associate($reader);
                $receivedBook->inventory_id = $item->id;
                $receivedBook->received_date = $dates['received_date'];
                $receivedBook->return_date = $dates['return_date'];
                $receivedBook->save();
            });
        return back();
    }
}
