<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\ReceivedBook;
use Illuminate\Contracts\Database\Eloquent\Builder;
use MoonShine\ActionButtons\ActionButton;
use MoonShine\Components\FormBuilder;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\Hidden;
use MoonShine\Fields\ID;

class ReceivedBookResource extends ModelResource
{
    protected string $model = ReceivedBook::class;

    protected array $with = [
        'book',
        'bookStorageType',
        'book.publishingHouse',
        'book.language',
        'book.category',
        'book.schoolClass',
    ];

    protected array $search = [
        'book.name',
        'book.isbn',
        'count',
        'year',
    ];

    public function __construct(bool $isLazyLoad = false, ?array $search = null)
    {
        if($isLazyLoad) $this->with = [];

        if(!is_null($search)) {
            $this->search = $search;
        }
    }

    public function title(): string
    {
        return __('moonshine::ui.resource.received-books');
    }

    public function getActiveActions(): array
    {
        return ['view'];
    }

    public function query(): Builder
    {
        return parent::query()->where('organization_id', session('selected_admin')->organization_id);
    }

    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
                Hidden::make(__('moonshine::ui.resource.book_storage_type'), 'bookStorageType.name'),
                Hidden::make(__('moonshine::ui.resource.count'), 'count'),
                Hidden::make(__('moonshine::ui.resource.received-year'), 'year'),
                Hidden::make(__('moonshine::ui.resource.name'), 'book.name'),
                Hidden::make('ISBN', 'book.isbn'),
                Hidden::make(__('moonshine::ui.resource.category'), 'book.category.name'),
                Hidden::make(__('moonshine::ui.resource.published_year'), 'book.published_year'),
                Hidden::make(__('moonshine::ui.resource.school_class'), 'book.schoolClass.name'),
                Hidden::make(__('moonshine::ui.resource.language'), 'book.language.name'),
                Hidden::make(__('moonshine::ui.resource.authors'), 'book.authors'),
                Hidden::make(__('moonshine::ui.resource.publishing_house'), 'book.publishingHouse.name'),

            ]),
        ];
    }

    public function rules(Model $item): array
    {
        return [];
    }

    public function search(): array
    {
        return $this->search;
    }

    public function indexButtons(): array
    {
        return [

            ActionButton::make('', item: $this->getModel())
                ->inModal(
                    title: __('moonshine::ui.resource.print'),
                    content: fn (): string => (string) FormBuilder::make(
                            $this->route('organization_book.print', $this->uriKey())
                        )
                        ->submit(__('moonshine::ui.resource.to_print'), ['class' => 'btn-primary btn-lg'])
                        ->fields([
                            Hidden::make('id')->setValue($this->getItem()->id),
                        ])
                )
                ->primary()
                ->icon('heroicons.printer'),
        ];
    }
}
