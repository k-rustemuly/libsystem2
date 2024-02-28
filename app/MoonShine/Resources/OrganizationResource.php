<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Organization;
use Illuminate\Validation\Rule;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Enums\PageType;
use MoonShine\Fields\Date;
use MoonShine\Fields\Hidden;
use MoonShine\Fields\ID;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Text;

class OrganizationResource extends ModelResource
{
    protected string $model = Organization::class;

    protected ?PageType $redirectAfterSave = PageType::INDEX;

    public array $with = ['organizationType'];

    public function title(): string
    {
        return __('moonshine::ui.resource.organizations');
    }

    public function getActiveActions(): array
    {
        return ['create', 'view', 'update'];
    }

    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
                // MediaLibrary::make(__('moonshine::ui.resource.photo'), 'image')
                //     ->hideOnIndex()
                //     ->removable()
                //     ->allowedExtensions(['jpg', 'jpeg', 'png']),

                BelongsTo::make(__('moonshine::ui.resource.org_type'), 'organizationType', fn($item) => "$item->name", new OrganizationTypeResource())
                    ->required()
                    ->searchable(),

                Text::make(__('moonshine::ui.resource.bin'), 'bin')
                    ->required(),

                Hidden::make(__('moonshine::ui.resource.name'), 'name')
                    ->hideOnDetail()
                    ->hideOnForm(),

                Text::make(__('moonshine::ui.resource.name_kk'), 'name_kk')
                    ->hideOnIndex()
                    ->required(),

                Text::make(__('moonshine::ui.resource.name_ru'), 'name_ru')
                    ->hideOnIndex()
                    ->required(),

                Text::make(__('moonshine::ui.resource.kato'), 'kato')
                    ->hideOnIndex()
                    ->required(),

                Text::make(__('moonshine::ui.resource.short_name_kk'), 'short_name_kk')
                    ->hideOnIndex()
                    ->required(),

                Text::make(__('moonshine::ui.resource.short_name_ru'), 'short_name_ru')
                    ->hideOnIndex()
                    ->required(),

                Text::make(__('moonshine::ui.resource.short_type_kk'), 'short_type_kk')
                    ->hideOnIndex()
                    ->required(),

                Text::make(__('moonshine::ui.resource.short_type_ru'), 'short_type_ru')
                    ->hideOnIndex()
                    ->required(),

                Text::make(__('moonshine::ui.resource.legal_address_kk'), 'legal_address_kk')
                    ->hideOnIndex()
                    ->required(),

                Text::make(__('moonshine::ui.resource.legal_address_ru'), 'legal_address_ru')
                    ->hideOnIndex()
                    ->required(),

                Date::make(__('moonshine::ui.resource.created_at'), 'created_at')
                    ->format("d.m.Y")
                    ->default(now()->toDateTimeString())
                    ->sortable()
                    ->hideOnForm()
                    ->showOnExport(),
            ]),
        ];
    }

    public function rules(Model $item): array
    {
        return [
            'bin' => [
                'size:12',
                Rule::unique('organizations')->ignoreModel($item),
            ],
            'organization_type_id' => ['required', 'exists:organization_types,id'],
            'kato' => ['required', 'size:9'],
            'name_kk' => ['required', 'min:3'],
            'name_ru' => ['required', 'min:3'],
            'short_name_kk' => ['required', 'min:3'],
            'short_name_ru' => ['required', 'min:3'],
            'short_type_kk' => ['required', 'min:3'],
            'short_type_ru' => ['required', 'min:3'],
            'legal_address_kk' => ['required', 'min:3'],
            'legal_address_ru' => ['required', 'min:3'],
        ];
    }

    public function search(): array
    {
        return [
            'id',
            'bin',
            'name_kk',
            'name_ru',
        ];
    }
}
