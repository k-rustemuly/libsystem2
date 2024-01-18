<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\OrganizationBook;
use App\MoonShine\Controllers\OrganizationBookController;
use App\MoonShine\Pages\OrganizationBook\OrganizationBookIndexPage;
use App\MoonShine\Pages\OrganizationBook\OrganizationBookFormPage;
use App\MoonShine\Pages\OrganizationBook\OrganizationBookDetailPage;
use Illuminate\Contracts\Database\Eloquent\Builder;
use MoonShine\Resources\ModelResource;
use Illuminate\Support\Facades\Route;
use MoonShine\ActionButtons\ActionButton;
use MoonShine\Enums\PageType;

class OrganizationBookResource extends ModelResource
{
    protected string $model = OrganizationBook::class;

    protected ?PageType $redirectAfterSave = PageType::INDEX;

    protected array $with = [
        'book',
        'bookStorageType',
        'book.publishingHouse',
        'book.language',
        'book.category',
        'book.schoolClass',
    ];

    public function title(): string
    {
        return __('moonshine::ui.resource.books');
    }

    public function getActiveActions(): array
    {
        return ['create', 'view'];
    }

    public function query(): Builder
    {
        return parent::query()
            ->where('organization_id', session('selected_admin')?->organization_id);

    }

    public function pages(): array
    {
        return [
            OrganizationBookIndexPage::make($this->title()),
            OrganizationBookFormPage::make(
                $this->getItemID()
                    ? __('moonshine::ui.edit')
                    : __('moonshine::ui.add')
            ),
            OrganizationBookDetailPage::make(__('moonshine::ui.show')),
        ];
    }

    public function rules(Model $item): array
    {
        return [];
    }

    public function actions(): array
    {
        return [
            ActionButton::make(__('moonshine::ui.resource.received-books'), to_page(new OrganizationBookFormPage(), $this))
                ->icon('heroicons.archive-box-arrow-down')
                ->success()
        ];
    }

    protected function resolveRoutes(): void
    {
        parent::resolveRoutes();

        Route::post('/organization-book/create', [OrganizationBookController::class, 'store'])->name('organization_book.create');
    }

}
