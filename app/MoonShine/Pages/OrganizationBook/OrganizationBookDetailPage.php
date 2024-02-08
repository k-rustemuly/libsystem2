<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\OrganizationBook;

use App\MoonShine\Fields\Image;
use App\MoonShine\Resources\OrganizationBookInventoryResource;
use App\MoonShine\Resources\ReceivedBookResource;
use Illuminate\View\ComponentAttributeBag;
use MoonShine\Components\TableBuilder;
use MoonShine\Decorations\Block;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Grid;
use MoonShine\Decorations\Tab;
use MoonShine\Decorations\Tabs;
use MoonShine\Fields\Hidden;
use MoonShine\Fields\Relationships\HasMany;
use MoonShine\Pages\Crud\DetailPage;

class OrganizationBookDetailPage extends DetailPage
{
    public function breadcrumbs(): array
    {
        $breadcrumbs = parent::breadcrumbs();

        $breadcrumbs[$this->route()] = data_get($this->getResource()->getItem(), 'book.name');

        return $breadcrumbs;
    }

    public function fields(): array
    {
        return [
            Hidden::make(__('moonshine::ui.resource.book_storage_type'), 'bookStorageType.name'),
            Hidden::make(__('moonshine::ui.resource.count'), 'count'),
            Hidden::make(__('moonshine::ui.resource.name'), 'book.name'),
            Hidden::make('ISBN', 'book.isbn'),
            Hidden::make(__('moonshine::ui.resource.category'), 'book.category.name'),
            Hidden::make(__('moonshine::ui.resource.published_year'), 'book.published_year'),
            Hidden::make(__('moonshine::ui.resource.school_class'), 'book.schoolClass.name'),
            Hidden::make(__('moonshine::ui.resource.language'), 'book.language.name'),
            Hidden::make(__('moonshine::ui.resource.authors'), 'book.authors'),
            Hidden::make(__('moonshine::ui.resource.publishing_house'), 'book.publishingHouse.name'),

            HasMany::make(__('moonshine::ui.resource.received-books'), 'receivedBooks', resource: new ReceivedBookResource(true, search: ['count', 'year']))
                ->fields([
                    Hidden::make(__('moonshine::ui.resource.count'), 'count'),
                    Hidden::make(__('moonshine::ui.resource.price'), 'price'),
                    Hidden::make(__('moonshine::ui.resource.total'), 'total'),
                    Hidden::make(__('moonshine::ui.resource.received-year'), 'year'),
                    Hidden::make(__('moonshine::ui.resource.created_at'), 'created_at', fn($item) => $item->created_at->isoFormat('D MMMM YYYY, H:mm')),
                ]),

            HasMany::make(__('moonshine::ui.resource.inventory'), 'inventory', resource: new OrganizationBookInventoryResource()),
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
        $resource = $this->getResource();
        $item = $resource->getItem();
        return [
            Grid::make([
                Column::make([
                    Block::make([
                        Image::make(formatted: fn() => $item->book->cover)
                        ->forcePreview()
                    ])
                ])
                ->columnSpan(3),

                Column::make([
                    Block::make([
                        TableBuilder::make(
                            $resource
                                ->getDetailFields()
                                ->onlyFields()
                                ->withoutOutside()
                        )
                        ->cast($resource->getModelCast())
                        ->items([$item])
                        ->vertical()
                        ->simple()
                        ->preview()
                        ->tdAttributes(fn (
                            $data,
                            int $row,
                            int $cell,
                            ComponentAttributeBag $attributes
                        ): ComponentAttributeBag => $attributes->when(
                            $cell === 0,
                            fn (ComponentAttributeBag $attr): ComponentAttributeBag => $attr->merge([
                                'class' => 'font-semibold',
                                'width' => '20%',
                            ])
                        )),
                    ])

                    ])->columnSpan(9)
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
        $components[] =  Block::make([
            Tabs::make([
                ...$tabs
            ])
        ])
        ->customAttributes([
            'style' => 'margin-top:1.5rem'
        ]);
        return $components;
    }
}
