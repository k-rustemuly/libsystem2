<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Role;
use App\MoonShine\Pages\ChooseRole;
use App\MoonShine\Resources\AdminResource;
use App\MoonShine\Resources\BindingResource;
use App\MoonShine\Resources\BookResource;
use App\MoonShine\Resources\BookStorageTypeResource;
use App\MoonShine\Resources\CategoryResource;
use App\MoonShine\Resources\LanguageResource;
use App\MoonShine\Resources\OrganizationBookInventoryResource;
use App\MoonShine\Resources\OrganizationBookResource;
use App\MoonShine\Resources\OrganizationReaderResource;
use App\MoonShine\Resources\OrganizationResource;
use App\MoonShine\Resources\OrganizationTypeResource;
use App\MoonShine\Resources\PublishingHouseResource;
use App\MoonShine\Resources\ReceivedBookResource;
use App\MoonShine\Resources\SchoolClassResource;
use App\MoonShine\Resources\UserResource;
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
            MenuItem::make(__('moonshine::ui.resource.organizations'), new OrganizationResource())
                ->canSee(fn() => session('selected_admin')->role_id == Role::SUPER_ADMIN),

            MenuItem::make(__('moonshine::ui.resource.organization_types'), new OrganizationTypeResource())
                ->canSee(fn() => session('selected_admin')->role_id == Role::SUPER_ADMIN),

            MenuItem::make(__('moonshine::ui.resource.admins'), new AdminResource())
                ->canSee(fn() => session('selected_admin')->role_id == Role::SUPER_ADMIN),

            MenuItem::make(__('moonshine::ui.resource.users'), new UserResource())
                ->canSee(fn() => session('selected_admin')->role_id == Role::SUPER_ADMIN),

            MenuItem::make(__('moonshine::ui.resource.categories'), new CategoryResource())
                ->canSee(fn() => session('selected_admin')->role_id == Role::SUPER_ADMIN),

            MenuItem::make(__('moonshine::ui.resource.languages'), new LanguageResource())
                ->canSee(fn() => session('selected_admin')->role_id == Role::SUPER_ADMIN),

            MenuItem::make(__('moonshine::ui.resource.publishing_houses'), new PublishingHouseResource())
                ->canSee(fn() => session('selected_admin')->role_id == Role::SUPER_ADMIN),

            MenuItem::make(__('moonshine::ui.resource.school_classes'), new SchoolClassResource())
                ->canSee(fn() => session('selected_admin')->role_id == Role::SUPER_ADMIN),

            MenuItem::make(__('moonshine::ui.resource.bindings'), new BindingResource())
                ->canSee(fn() => session('selected_admin')->role_id == Role::SUPER_ADMIN),

            MenuItem::make(__('moonshine::ui.resource.book_storage_types'), new BookStorageTypeResource())
                ->canSee(fn() => session('selected_admin')->role_id == Role::SUPER_ADMIN),

            MenuItem::make(__('moonshine::ui.resource.books'), new BookResource())
                ->canSee(fn() => session('selected_admin')->role_id == Role::SUPER_ADMIN),

            MenuItem::make(__('moonshine::ui.resource.books'), new OrganizationBookResource())
                ->canSee(fn() => session('selected_admin')->role_id == Role::LIBRARIAN),

            MenuItem::make(__('moonshine::ui.resource.received-books'), new ReceivedBookResource())
                ->canSee(fn() => session('selected_admin')->role_id == Role::LIBRARIAN),

            MenuItem::make(__('moonshine::ui.resource.readers'), new OrganizationReaderResource())
                ->canSee(fn() => session('selected_admin')->role_id == Role::LIBRARIAN),

            MenuGroup::make(static fn() => __('moonshine::ui.resource.system'), [
                MenuItem::make(
                    static fn() => __('moonshine::ui.resource.admins_title'),
                    new MoonShineUserResource()
                ),
                MenuItem::make(
                    static fn() => __('moonshine::ui.resource.role_title'),
                    new MoonShineUserRoleResource()
                ),
            ])
            ->canSee(fn() => session('selected_admin')->role_id == Role::SUPER_ADMIN),

            MenuItem::make('000', new OrganizationBookInventoryResource())
                ->canSee(fn() => false),
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
