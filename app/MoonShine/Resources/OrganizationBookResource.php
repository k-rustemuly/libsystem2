<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\OrganizationBook;
use App\MoonShine\Pages\OrganizationBook\OrganizationBookIndexPage;
use App\MoonShine\Pages\OrganizationBook\OrganizationBookFormPage;
use App\MoonShine\Pages\OrganizationBook\OrganizationBookDetailPage;
use Illuminate\Contracts\Database\Eloquent\Builder;
use MoonShine\Resources\ModelResource;

class OrganizationBookResource extends ModelResource
{
    protected string $model = OrganizationBook::class;

    protected string $title = 'OrganizationBooks';

    protected array $with = [
        'book',
        'book.publishingHouse',
        'book.language',
        'book.category',
        'book.schoolClass',
    ];

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
}
