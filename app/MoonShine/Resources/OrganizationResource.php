<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Organization;

use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\Date;
use MoonShine\Fields\Hidden;
use MoonShine\Fields\ID;
use MoonShine\Fields\Text;

class OrganizationResource extends ModelResource
{
    protected string $model = Organization::class;

    protected string $title = 'Organizations';

    public array $with = ['organizationType'];

    public function title(): string
    {
        return trans('moonshine::ui.resource.organizations');
    }

    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
                // MediaLibrary::make(trans('moonshine::ui.resource.photo'), 'image')
                //     ->hideOnIndex()
                //     ->removable()
                //     ->allowedExtensions(['jpg', 'jpeg', 'png']),

                // BelongsTo::make(trans('moonshine::ui.resource.org_type'), 'organizationType', fn($item) => "$item->name")
                //     ->required()
                //     ->searchable(),

                Text::make(trans('moonshine::ui.resource.bin'), 'bin')
                    ->required(),

                Hidden::make(trans('moonshine::ui.resource.name'), 'name')
                    ->hideOnDetail()
                    ->hideOnForm(),

                Text::make(trans('moonshine::ui.resource.name_kk'), 'name_kk')
                    ->hideOnIndex()
                    ->required(),

                Text::make(trans('moonshine::ui.resource.name_ru'), 'name_ru')
                    ->hideOnIndex()
                    ->required(),

                Text::make(trans('moonshine::ui.resource.kato'), 'kato')
                    ->hideOnIndex()
                    ->required(),

                Text::make(trans('moonshine::ui.resource.short_name_kk'), 'short_name_kk')
                    ->hideOnIndex()
                    ->required(),

                Text::make(trans('moonshine::ui.resource.short_name_ru'), 'short_name_ru')
                    ->hideOnIndex()
                    ->required(),

                Text::make(trans('moonshine::ui.resource.short_type_kk'), 'short_type_kk')
                    ->hideOnIndex()
                    ->required(),

                Text::make(trans('moonshine::ui.resource.short_type_ru'), 'short_type_ru')
                    ->hideOnIndex()
                    ->required(),

                Text::make(trans('moonshine::ui.resource.legal_address_kk'), 'legal_address_kk')
                    ->hideOnIndex()
                    ->required(),

                Text::make(trans('moonshine::ui.resource.legal_address_ru'), 'legal_address_ru')
                    ->hideOnIndex()
                    ->required(),

                Date::make(trans('moonshine::ui.resource.created_at'), 'created_at')
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
        return [];
    }
}
