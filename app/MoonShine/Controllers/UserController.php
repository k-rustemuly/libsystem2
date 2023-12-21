<?php

declare(strict_types=1);

namespace App\MoonShine\Controllers;

use App\Models\User;
use MoonShine\MoonShineRequest;
use MoonShine\Http\Controllers\MoonShineController;
use Symfony\Component\HttpFoundation\Response;

final class UserController extends MoonShineController
{
    public function search(MoonShineRequest $request): Response
    {
        return response()
            ->json(
                User::search($request->get('query'))->get()->map(function ($item) {
                    return [
                        'value' => $item->id,
                        'label' => $item->name. " ($item->iin)",
                    ];
                })->all()
            );
    }
}
