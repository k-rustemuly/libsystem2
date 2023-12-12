<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use Illuminate\Validation\Rule;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Grid;
use MoonShine\Fields\Date;
use MoonShine\Fields\ID;
use MoonShine\Fields\Text;

class CategoryResource extends ModelResource
{
    protected string $model = Category::class;

    public function title(): string
    {
        return __('moonshine::ui.resource.categories');
    }

    public function fields(): array
    {
        return [
            Grid::make([
                Column::make([
                    Block::make(
                        [
                            ID::make()
                                ->sortable()
                                ->useOnImport()
                                ->showOnExport(),

                            Text::make('slug')
                                ->required(),

                            Text::make(__('moonshine::ui.resource.name_kk'), 'name_kk')
                                ->required(),

                            Text::make(__('moonshine::ui.resource.name_ru'), 'name_ru')
                                ->required(),

                            Date::make(
                                __('moonshine::ui.resource.created_at'),
                                'created_at'
                            )
                                ->format("d.m.Y")
                                ->default(now()->toDateTimeString())
                                ->sortable()
                                ->hideOnForm()
                                ->showOnExport(),
                        ]
                    ),
                ]),
            ]),
        ];
    }

    public function rules(Model $item): array
    {
        return [
            'slug' => [
                'required',
                Rule::unique('categories')->ignoreModel($item),
            ],
            'name_kk' => ['required', 'min:3'],
            'name_ru' => ['required', 'min:3'],
        ];
    }

    public function search(): array
    {
        return [
            'id',
            'slug',
            'name_kk',
            'name_ru',
        ];
    }
}
