<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\OrganizationBook;

use App\MoonShine\Resources\BookStorageTypeResource;
use MoonShine\Components\FormBuilder;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Fragment;
use MoonShine\Decorations\Grid;
use MoonShine\Fields\Fields;
use MoonShine\Fields\Hidden;
use MoonShine\Fields\Json;
use MoonShine\Fields\Number;
use MoonShine\Fields\Position;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Select;
use MoonShine\Pages\Crud\FormPage;

class OrganizationBookFormPage extends FormPage
{
    public function fields(): array
    {
        return [
            Grid::make([
                Column::make([
                    BelongsTo::make(__('moonshine::ui.resource.book_storage_type'), 'bookStorageType', fn($item) => $item->name, new BookStorageTypeResource())
                        ->required(),
                ])->columnSpan(6),
                Column::make([
                    Select::make(__('moonshine::ui.resource.received-year'), 'year')
                        ->options(
                            function() {
                                $years = range(date('Y'), 1950);
                                return array_combine($years, $years);
                            }
                        )
                        ->searchable()
                        ->required(),
                ])->columnSpan(6),
            ]),
            Json::make(__('moonshine::ui.resource.books'), 'books')
                ->fields([
                    Position::make(),
                    Select::make(__('moonshine::ui.resource.book'), 'book_id')
                        ->searchable()
                        ->required()
                        ->async(route('admin.books.search')),
                    Number::make(__('moonshine::ui.resource.price'), 'price')
                        ->min(0)
                        ->placeholder(__('moonshine::ui.resource.price_placeholder'))
                        ->buttons(),
                    Number::make(__('moonshine::ui.resource.book_count'), 'count')
                        ->min(1)
                        ->buttons()
                        ->required()
                        ->default(1)
                ])
                ->sortable()
                ->creatable()
                ->removable(),


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

        $action = $resource->route( 'organization_book.create',
            $item?->getKey()
        );

        $isForceAsync = request('_async_form', false);
        $isAsync = $resource->isAsync() || $isForceAsync;

        return [
            Fragment::make([
                FormBuilder::make($action)
                    ->fillCast(
                        $item,
                        $resource->getModelCast()
                    )
                    ->fields(
                        $resource
                            ->getFormFields()
                            ->when(
                                ! is_null($item),
                                fn (Fields $fields): Fields => $fields->push(
                                    Hidden::make('_method')->setValue('PUT')
                                )
                            )
                            ->when(
                                ! $item?->exists && ! $resource->isCreateInModal(),
                                fn (Fields $fields): Fields => $fields->push(
                                    Hidden::make('_force_redirect')->setValue(true)
                                )
                            )
                            ->toArray()
                    )
                    ->when(
                        $isAsync,
                        fn (FormBuilder $formBuilder): FormBuilder => $formBuilder
                            ->async(asyncEvents: 'table-updated-' . request('_tableName', 'default'))
                    )
                    ->when(
                        $resource->isPrecognitive() || (moonshineRequest()->isFragmentLoad('crud-form') && ! $isAsync),
                        fn (FormBuilder $form): FormBuilder => $form->precognitive()
                    )
                    ->name('crud')
                    ->submit(__('moonshine::ui.save'), ['class' => 'btn-primary btn-lg']),
            ])
                ->name('crud-form')
                ->updateAsync(['resourceItem' => $resource->getItemID()]),
        ];
    }

    protected function bottomLayer(): array
    {
        return [
            ...parent::bottomLayer()
        ];
    }
}
