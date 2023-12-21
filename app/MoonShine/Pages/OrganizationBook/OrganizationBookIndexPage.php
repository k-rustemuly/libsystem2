<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\OrganizationBook;

use App\MoonShine\Components\Cards;
use MoonShine\Buttons\DeleteButton;
use MoonShine\Buttons\DetailButton;
use MoonShine\Buttons\EditButton;
use MoonShine\Buttons\ExportButton;
use MoonShine\Buttons\FiltersButton;
use MoonShine\Buttons\ImportButton;
use MoonShine\Buttons\MassDeleteButton;
use MoonShine\Components\ActionGroup;
use MoonShine\Components\TableBuilder;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Flex;
use MoonShine\Decorations\Fragment;
use MoonShine\Decorations\Grid;
use MoonShine\Decorations\LineBreak;
use MoonShine\Decorations\Tab;
use MoonShine\Decorations\Tabs;
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
            Preview::make('bookStorageType', 'bookStorageType', fn($item) => $item->bookStorageType->name)->badge(fn($item) => $item->id == 1 ? 'green' : 'info'),
            Hidden::make('book.name'),
            Hidden::make('book.isbn'),
            Hidden::make(__('moonshine::ui.resource.count'), 'count'),
            Hidden::make(__('moonshine::ui.resource.category'), 'book.category.name'),
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
                    ->badge('bookStorageType')
                    ->thumbnail('book.cover')
                    ->title('book.name')
                    ->subTitle('book.isbn')
                    ->columnSpan(4)
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

    protected function actionButtons(): array
    {
        return [
            Grid::make([
                Column::make([
                    Flex::make([
                        ActionGroup::make([
                            ...$this->getResource()->actions(),
                        ]),
                    ])->justifyAlign('start'),

                    ActionGroup::make()->when(
                        $this->getResource()->filters() !== [],
                        fn (ActionGroup $group): ActionGroup => $group->add(
                            FiltersButton::for($this->getResource())
                        )
                    )->when(
                        ! is_null($export = $this->getResource()->export()),
                        fn (ActionGroup $group): ActionGroup => $group->add(
                            ExportButton::for($this->getResource(), $export)
                        ),
                    )->when(
                        ! is_null($import = $this->getResource()->import()),
                        fn (ActionGroup $group): ActionGroup => $group->add(
                            ImportButton::for($this->getResource(), $import)
                        ),
                    ),
                ])->customAttributes([
                    'class' => 'flex flex-wrap items-center justify-between gap-2 sm:flex-nowrap',
                ]),
            ]),
            LineBreak::make(),
        ];
    }
}
