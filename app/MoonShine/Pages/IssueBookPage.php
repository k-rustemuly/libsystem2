<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use Illuminate\Database\Eloquent\Model;
use MoonShine\Components\FormBuilder;
use MoonShine\Decorations\Block;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Grid;
use MoonShine\Decorations\LineBreak;
use MoonShine\Fields\DateRange;
use MoonShine\Fields\Json;
use MoonShine\Fields\Position;
use MoonShine\Fields\Select;
use MoonShine\Fields\Text;
use MoonShine\Pages\Page;

class IssueBookPage extends Page
{
    public function breadcrumbs(): array
    {
        return [
            '#' => $this->title()
        ];
    }

    public function title(): string
    {
        return __('moonshine::ui.resource.issue_book');
    }

    public function components(): array
	{
		return [
            Block::make([
                FormBuilder::make(route('admin.books.mass.receive'))
                    ->fields([
                        Grid::make([
                            Column::make([
                                Select::make(__('moonshine::ui.resource.user_fullname'), 'user_id')
                                    ->onBeforeApply(function(Model $item) {
                                        $item->organization_id = session('selected_admin')?->organization_id;
                                        return $item;
                                    })
                                    ->required()
                                    ->searchable()
                                    ->async(route('moonshine.users.search', [$this->uriKey()]))
                            ])->columnSpan(4),
                            Column::make([
                                DateRange::make(__('moonshine::ui.resource.receive_and_return_date'), 'date')
                                    ->fromTo('received_date', 'return_date')
                                    ->required()
                            ])->columnSpan(8)
                        ]),
                        LineBreak::make(),
                        Json::make(__('moonshine::ui.resource.books'), 'books')
                            ->fields([
                                Position::make(),
                                Select::make(__('moonshine::ui.resource.book'), 'inventory_id')
                                    ->searchable()
                                    ->required()
                                    ->async(route('admin.books.search.inventory')),
                                Text::make(__('moonshine::ui.resource.comment'), 'comment')
                            ])
                            ->sortable()
                            ->creatable()
                            ->removable(),
                    ]),
            ])
        ];
	}
}
