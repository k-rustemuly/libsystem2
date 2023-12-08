<?php

declare(strict_types=1);

namespace App\Providers;

use App\MoonShine\Pages\ChooseRole;
use Illuminate\Support\Facades\Vite;
use MoonShine\Providers\MoonShineApplicationServiceProvider;
use MoonShine\Menu\MenuGroup;
use MoonShine\Menu\MenuItem;
use MoonShine\Resources\MoonShineUserResource;
use MoonShine\Resources\MoonShineUserRoleResource;

class MoonShineServiceProvider extends MoonShineApplicationServiceProvider
{
    public function boot(): void
    {
        parent::boot();

        moonshineAssets()->add([
            Vite::asset('resources/css/app.css'),
            Vite::asset('resources/js/app.js'),
        ]);
    }

    protected function pages(): array
    {
        return [
            ChooseRole::make()
        ];
    }

    protected function menu(): array
    {
        return [
            MenuGroup::make(static fn() => __('moonshine::ui.resource.system'), [
                MenuItem::make(
                    static fn() => __('moonshine::ui.resource.admins_title'),
                    new MoonShineUserResource()
                ),
                MenuItem::make(
                    static fn() => __('moonshine::ui.resource.role_title'),
                    new MoonShineUserRoleResource()
                ),
            ]),
        ];
    }

    /**
     * @return array{css: string, colors: array, darkColors: array}
     */
    protected function theme(): array
    {
        return [];
    }
}
