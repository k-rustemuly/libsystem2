<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\OrganizationReader;

use App\MoonShine\Resources\BookResource;
use App\MoonShine\Resources\OrganizationBookInventoryResource;
use App\MoonShine\Resources\OrganizationBookTransactionResource;
use MoonShine\Buttons\CreateButton;
use MoonShine\Components\ActionGroup;
use MoonShine\Decorations\Block;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Flex;
use MoonShine\Decorations\Grid;
use MoonShine\Decorations\LineBreak;
use MoonShine\Decorations\Tab;
use MoonShine\Decorations\Tabs;
use MoonShine\Fields\ID;
use MoonShine\Fields\Preview;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Relationships\MorphMany;
use MoonShine\Fields\Text;
use MoonShine\Pages\Crud\DetailPage;

class OrganizationReaderDetailPage extends DetailPage
{
    public function fields(): array
    {
        return [
            ID::make()->sortable(),
            Preview::make(__('moonshine::ui.resource.user_fullname'), 'user.name'),
            Preview::make(__('moonshine::ui.resource.iin'), 'user.iin'),
            Preview::make(__('moonshine::ui.resource.debt'), 'debt'),
            MorphMany::make(__('moonshine::ui.resource.books'), 'transactions', resource: new OrganizationBookTransactionResource())
                ->fields([
                    ID::make()->sortable(),
                    BelongsTo::make(__('moonshine::ui.resource.inventory_number'), 'inventory', fn($item) => $item->code, new OrganizationBookInventoryResource()),
                    BelongsTo::make(__('moonshine::ui.resource.book'), 'book', fn($item) => $item->name, new BookResource()),
                    Text::make(__('moonshine::ui.resource.received_date'), 'received_date')->sortable(),
                    Text::make(__('moonshine::ui.resource.return_date'), 'return_date')->sortable(),
                    Text::make(__('moonshine::ui.resource.returned_date'), 'returned_date')->sortable(),
                    Text::make(__('moonshine::ui.resource.comment'), 'comment'),
                ])
        ];
    }

    protected function bottomLayer(): array
    {
        $components = [];
        $item = $this->getResource()->getItem();

        if (! $item?->exists) {
            return $components;
        }

        $outsideFields = $this->getResource()->getDetailFields(onlyOutside: true);
        $tabs = [];
        if ($outsideFields->isNotEmpty()) {
            foreach ($outsideFields as $field) {
                $field->resolveFill(
                    $item?->attributesToArray() ?? [],
                    $item
                );
                $tabs[] = Tab::make($field->label(), [$field]);
            }
        }

        $components[] = Grid::make([
                LineBreak::make(),
                Column::make([
                    Flex::make([
                        ActionGroup::make([
                            CreateButton::for($this->getResource(), 'index-table'),
                        ]),
                    ])->justifyAlign('start'),

                ])->customAttributes([
                    'class' => 'flex flex-wrap items-center justify-between gap-2 sm:flex-nowrap',
                ]),
                Column::make([
                    Block::make([

                        Tabs::make([
                            ...$tabs
                        ])
                    ])
                    ->customAttributes([
                        'style' => 'margin-top:1.5rem'
                    ])
                ])
            ]);
        return $components;
    }
}
