<?php

declare(strict_types=1);

namespace App\MoonShine\Components;

use Illuminate\Support\Facades\Storage;
use MoonShine\Components\MoonShineComponent;

/**
 * @method static static make()
 */
final class Profile extends MoonShineComponent
{
    protected string $view = 'components.layout.profile';

    protected function viewData(): array
    {
        $user = auth()->user();

        $avatar = $user?->{config('moonshine.auth.fields.avatar', 'avatar')};
        $nameOfUser = $user?->{config('moonshine.auth.fields.name', 'name')};
        $username = $user?->{config('moonshine.auth.fields.username', 'email')};

        $avatar = $avatar
            ? Storage::disk(config('moonshine.disk', 'public'))
                ->url($avatar)
            : "https://ui-avatars.com/api/?name=$nameOfUser";

        return [
            'avatar' => $avatar,
            'nameOfUser' => $nameOfUser,
            'username' => $username,
        ];
    }
}
