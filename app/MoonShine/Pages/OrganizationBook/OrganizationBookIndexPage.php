<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\OrganizationBook;

use App\MoonShine\Components\Cards;
use MoonShine\Buttons\DeleteButton;
use MoonShine\Buttons\DetailButton;
use MoonShine\Buttons\EditButton;
use MoonShine\Decorations\Fragment;
use MoonShine\Fields\Hidden;
use MoonShine\Fields\Preview;
use MoonShine\Fields\Image;
use MoonShine\Pages\Crud\IndexPage;

class OrganizationBookIndexPage extends IndexPage
{
    public function fields(): array
    {
        return [
            Image::make('book.cover'),
            Preview::make('book.category.name')->badge('success'),
            Hidden::make('book.name'),
            Hidden::make('book.isbn'),
            Hidden::make(__('moonshine::ui.resource.count'), 'count'),
            Hidden::make(__('moonshine::ui.resource.published_year'), 'book.published_year'),
            Hidden::make(__('moonshine::ui.resource.school_class'), 'book.schoolClass.name'),
            Hidden::make(__('moonshine::ui.resource.language'), 'book.language.name'),
            Hidden::make(__('moonshine::ui.resource.authors'), 'book.authors'),
            Hidden::make(__('moonshine::ui.resource.publishing_house'), 'book.publishingHouse.name'),
        ];
    }

    protected function topLayer(): array
    {
        return [
            ...parent::topLayer()
        ];
    }

    protected function mainLayer(): array
    {
        return [...$this->filtersForm(), ...$this->actionButtons(), ...$this->queryTags(), ...$this->cards()];
    }

    protected function cards(): array
    {
        $cardName = 'index-card';

        $items = $this->getResource()->isPaginationUsed()
            ? $this->getResource()->paginate()
            : $this->getResource()->items();

        return [
            Fragment::make([
                Cards::make(items: $items)
                    ->name($cardName)
                    ->fields(fn () => $this->getResource()->getIndexFields()->toArray())
                    ->cast($this->getResource()->getModelCast())
                    ->badge('book.category.name')
                    ->thumbnail('book.cover')
                    ->title('book.name')
                    ->subTitle('book.isbn')
                    ->columnSpan(3)
                    ->overlay(true)
                    ->buttons([
                        ...$this->getResource()->getIndexButtons(),
                        DetailButton::for($this->getResource()),
                        EditButton::for($this->getResource(), $cardName),
                        DeleteButton::for($this->getResource(), $cardName),
                    ]),
            ])->name('crud-card'),
        ];
    }

    protected function bottomLayer(): array
    {
        return [
            ...parent::bottomLayer()
        ];
    }
}
