<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\BookStorageType;

use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Text;

class BookStorageTypeResource extends ModelResource
{
    protected string $model = BookStorageType::class;


    public function title(): string
    {
        return __('moonshine::ui.resource.book_storage_types');
    }

    public function getActiveActions(): array
    {
        return ['view', 'update'];
    }

    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
                Text::make(__('moonshine::ui.resource.name_kk'), 'name_kk')
                    ->required(),
                Text::make(__('moonshine::ui.resource.name_ru'), 'name_ru')
                    ->required(),
            ]),
        ];
    }

    public function rules(Model $item): array
    {
        return [
            'name_kk' => ['required', 'min:3'],
            'name_ru' => ['required', 'min:3'],
        ];
    }
}
