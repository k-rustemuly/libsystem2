<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Admin;

use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Switcher;

class AdminResource extends ModelResource
{
    protected string $model = Admin::class;

    public array $with = [
        'user',
        'organization',
        'role'
    ];

    public function title(): string
    {
        return __('moonshine::ui.resource.admins');
    }

    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
                BelongsTo::make(__('moonshine::ui.resource.organization_name'), 'organization', fn($item) => "$item->name ($item->bin)", new OrganizationResource())
                ->searchable(),
                BelongsTo::make(__('moonshine::ui.resource.user_fullname'), 'user', fn($item) => "$item->name", new UserResource())
                    ->searchable(),
                BelongsTo::make(__('moonshine::ui.resource.user_role'), 'role', fn($item) => "$item->name", new RoleResource())
                    ->searchable(),
                Switcher::make(__('moonshine::ui.resource.access'), 'is_active')
                    ->updateOnPreview()
            ]),
        ];
    }

    public function rules(Model $item): array
    {
        return [
            'is_active' => ['required', 'boolean']
        ];
    }

    public function search(): array
    {
        return [
            'id',
            'organization.name_ru',
            'organization.name_kk',
            'user.name',
            'role.name_kk',
            'role.name_ru',
        ];
    }
}
