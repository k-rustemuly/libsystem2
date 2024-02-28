<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\OrganizationBookInventory;
use App\MoonShine\Controllers\OrganizationBookController;
use Illuminate\Support\Facades\Route;
use MoonShine\ActionButtons\ActionButton;
use MoonShine\Components\FormBuilder;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\DateRange;
use MoonShine\Fields\Hidden;
use MoonShine\Fields\HiddenIds;
use MoonShine\Fields\ID;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Select;

class OrganizationBookInventoryResource extends ModelResource
{
    protected string $model = OrganizationBookInventory::class;

    protected array $with = ['transaction'];

    public function getActiveActions(): array
    {
        return ['view'];
    }

    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
                Hidden::make(__('moonshine::ui.resource.barcode'), 'code'),
                BelongsTo::make(__('moonshine::ui.resource.reader'), 'transaction',  fn($item) => $item->recipientable?->previewName, new OrganizationBookTransactionResource())
        ]),
        ];
    }

    public function rules(Model $item): array
    {
        return [];
    }

    public function indexButtons(): array
    {
        return [
            // ActionButton::make(__('moonshine::ui.resource.receive_to_reader'), item: $this->getModel())
            //     ->inModal(
            //         title: __('moonshine::ui.resource.receive_to_reader'),
            //         content: fn (): string => (string) FormBuilder::make($this->route('organization_book.receive', $this->uriKey()))
            //             ->submit(__('moonshine::ui.resource.receive'), ['class' => 'btn-primary btn-lg'])
            //             ->fields([
            //                 HiddenIds::make(),

            //                 Select::make('Читатель', 'user_id')
            //                     ->searchable()
            //                     ->required()
            //                     ->async(asyncUrl: route('moonshine.users.search', [$this->uriKey()])),

            //                 DateRange::make('Дата выдачи и возврата', 'date')
            //                     ->fromTo('received_date', 'return_date')
            //                     ->required()

            //             ])
            //             ->async(asyncEvents: ['table-updated-inventory'])
            //     )
            //     ->bulk()
            //     ->success()
            //     ->icon('heroicons.arrow-up-tray'),

            ActionButton::make(__('moonshine::ui.resource.print'), item: $this->getModel())
                ->inModal(
                    title: __('moonshine::ui.resource.print'),
                    content: fn (): string => (string) FormBuilder::make($this->route('organization_book.print', $this->uriKey()))
                        ->submit(__('moonshine::ui.resource.to_print'), ['class' => 'btn-primary btn-lg'])
                        ->fields([
                            HiddenIds::make(),
                        ])
                )
                ->bulk()
                ->primary()
                ->icon('heroicons.printer'),
        ];
    }

    protected function resolveRoutes(): void
    {
        parent::resolveRoutes();

        Route::post('/organization-book/receive', [OrganizationBookController::class, 'receive'])->name('organization_book.receive');
        Route::post('/organization-book/print', [OrganizationBookController::class, 'print'])->name('organization_book.print');
    }

}
