<?php

declare(strict_types=1);

namespace App\MoonShine\Controllers;

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
}
