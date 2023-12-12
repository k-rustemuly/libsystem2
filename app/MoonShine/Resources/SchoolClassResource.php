<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\SchoolClass;

use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\Date;
use MoonShine\Fields\ID;
use MoonShine\Fields\Number;
use Illuminate\Http\Request;

class SchoolClassResource extends ModelResource
{
    protected string $model = SchoolClass::class;

    public function title(): string
    {
        return __('moonshine::ui.resource.school_classes');
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
                Number::make(__('moonshine::ui.resource.class'), 'name')
                    ->min(0)
                    ->max(12)
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
            'name' => ['required', 'integer', 'min:0'],
        ];
    }

    public function search(): array
    {
        return [
            'name',
        ];
    }

    // public function canSee(Request $callback): static
    // {
    //     $this->canSeeCallback = $callback;

    //     return $this;
    // }
}
