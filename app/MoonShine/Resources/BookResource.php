<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Book;
use App\Models\Role;
use Illuminate\Contracts\Database\Eloquent\Builder;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Select;
use MoonShine\Fields\Text;

class BookResource extends ModelResource
{
    protected string $model = Book::class;

    public array $with = [
        'media',
        'publishingHouse',
        'language',
        'category',
        'schoolClass',
        'binding',
    ];
    public function getActiveActions(): array
    {
        if(session('selected_admin')->role_id == Role::LIBRARIAN)
            return ['view'];
        return ['create', 'view', 'update', 'delete', 'massDelete'];
    }
    public function title(): string
    {
        return __('moonshine::ui.resource.books');
    }

    public function query(): Builder
    {
        return parent::query()
            ->where('id', '>', 0);

    }

    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),

                Text::make('ISBN', 'isbn')
                    ->sortable()
                    ->copy()
                    ->required(),

                Text::make(__('moonshine::ui.resource.name'), 'name')
                    ->required(),

                BelongsTo::make(__('moonshine::ui.resource.category'), 'category', fn($item) => "$item->name", new CategoryResource())
                    ->required()
                    ->searchable(),

                Text::make(__('moonshine::ui.resource.authors'), 'authors')
                    ->required(),

                BelongsTo::make(__('moonshine::ui.resource.publishing_house'), 'publishingHouse', fn($item) => "$item->name", new PublishingHouseResource())
                    ->required()
                    ->searchable(),

                BelongsTo::make(__('moonshine::ui.resource.language'), 'language', fn($item) => "$item->name", new LanguageResource())
                    ->required()
                    ->searchable(),

                BelongsTo::make(__('moonshine::ui.resource.school_class'), 'schoolClass', fn($item) => "$item->name", new SchoolClassResource())
                    ->required()
                    ->searchable(),

                Select::make(__('moonshine::ui.resource.published_year'), 'published_year')
                    ->options(array_combine(range(date('Y'), 1950), range(date('Y'), 1950)))
                    ->searchable()
                    ->required(),

                Text::make(__('moonshine::ui.resource.udk'), 'udk')
                    ->hideOnIndex(),

                Text::make(__('moonshine::ui.resource.bbk'), 'bbk')
                    ->hideOnIndex()
            ]),
        ];
    }

    public function rules(Model $item): array
    {
        return [
            'isbn' => ['required', 'string'],
            'name' => ['required', 'min:2'],
            'category_id' => ['required', 'exists:categories,id'],
            'authors' => ['required'],
            'publishing_house_id' => ['required', 'exists:publishing_houses,id'],
            'language_id' => ['required', 'exists:languages,id'],
            'binding_id' => ['required', 'exists:bindings,id'],
            'school_class_id' => ['nullable', 'exists:school_classes,id'],
            'published_year' => ['required', 'integer', 'between:1950,'.date('Y')],
            'udk' => ['nullable'],
            'bbk' => ['nullable'],
        ];
    }

    public function search(): array
    {
        return [
            'id',
            'isbn',
            'name',
            'authors',
            'published_year'
        ];
    }
}
