<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Language;

use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\Date;
use MoonShine\Fields\ID;
use MoonShine\Fields\Text;

class LanguageResource extends ModelResource
{
    protected string $model = Language::class;

	public function title(): string
    {
        return __('moonshine::ui.resource.languages');
    }

    public function fields(): array
    {
        return [
            Block::make([
                ID::make()
                    ->sortable()
                    ->useOnImport()
                    ->showOnExport(),

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

    public function search(): array
    {
        return [
            'id',
            'name_kk',
            'name_ru',
        ];
    }

}
