<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\OrganizationBookTransaction;
use Illuminate\Database\Eloquent\Model;
use MoonShine\ActionButtons\ActionButton;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\Date;
use MoonShine\Fields\Hidden;
use MoonShine\Fields\HiddenIds;
use MoonShine\Fields\ID;
use MoonShine\Fields\Text;
use MoonShine\TypeCasts\ModelCast;

class OrganizationBookTransactionResource extends ModelResource
{
    protected string $title = 'OrganizationBookTransactions';

    protected array $with = [
        'book',
        'inventory'
    ];

    public function getActiveActions(): array
    {
        return [];
    }

    public function getModel(): Model
    {
        return new OrganizationBookTransaction(['organization_id' => session('selected_admin')?->organization_id]);
    }

    public function getModelCast(): ModelCast
    {
        return ModelCast::make(OrganizationBookTransaction::class);
    }


    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
                Text::make('received_date', 'received_date'),
            ]),
        ];
    }

    public function rules(Model $item): array
    {
        return [];
    }

    public function search(): array
    {
        return [
            'received_date',
            'return_date',
            'comment',
            'book.name',
            'book.isbn',
            'inventory.code'
        ];
    }

    public function buttons(): array
    {
        return [
            ActionButton::make(__('moonshine::ui.resource.accept_book'))
                ->inModal(fn () => __('moonshine::ui.resource.accept_book'), fn (Model $model): string => (string) form(
                        route('admin.books.accept'),
                        fields: [
                            Hidden::make('id')->setValue($model->id),
                            Date::make(__('moonshine::ui.resource.returned_date'), 'returned_date')
                                ->required(),
                            Text::make(__('moonshine::ui.resource.comment'), 'comment'),
                        ]
                    )
                    ->async(asyncEvents: ['table-updated-transactions'])
                )
                ->canSee(fn(Model $model) => is_null($model->returned_date))
                ->icon('heroicons.arrow-down-tray'),
        ];
    }

}
