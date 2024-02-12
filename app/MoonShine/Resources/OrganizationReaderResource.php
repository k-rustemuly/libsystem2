<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\OrganizationReader;
use App\MoonShine\Controllers\UserController;
use App\MoonShine\Pages\OrganizationReader\OrganizationReaderDetailPage;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use MoonShine\Enums\PageType;
use MoonShine\Fields\ID;
use MoonShine\Fields\Preview;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Pages\Crud\FormPage;
use MoonShine\Pages\Crud\IndexPage;

class OrganizationReaderResource extends ModelResource
{

    protected string $model = OrganizationReader::class;

    protected array $with = [
        'user',
    ];

    protected ?PageType $redirectAfterSave = PageType::INDEX;

    public function title(): string
    {
        return __('moonshine::ui.resource.readers');
    }

    public function query(): Builder
    {
        if ($this->isNowOnDetail()) {
            return parent::query();
        }

        return parent::query()
            ->where('organization_id', session('selected_admin')?->organization_id);
    }

    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
                Preview::make(__('moonshine::ui.resource.user_fullname'), 'user.name')->hideOnForm(),
                Preview::make(__('moonshine::ui.resource.iin'), 'user.iin')->hideOnForm(),
                Preview::make(__('moonshine::ui.resource.debt'), 'debt')->sortable()->hideOnForm(),
                BelongsTo::make(__('moonshine::ui.resource.user_fullname'), 'user', fn($item) => $item->name. ' ('.$item->iin.')', new UserResource())
                    ->hideOnIndex()
                    ->onBeforeApply(function(Model $item) {
                        $item->organization_id = session('selected_admin')?->organization_id;
                        return $item;
                    })
                    ->asyncSearch(
                        url: route('moonshine.users.search', [$this->uriKey()])
                    )
                    ->hideOnDetail()
            ]),
        ];
    }

    public function pages(): array
    {
        return [
            IndexPage::make($this->title()),
            FormPage::make(
                $this->getItemID()
                    ? __('moonshine::ui.edit')
                    : __('moonshine::ui.add')
            ),
            OrganizationReaderDetailPage::make(__('moonshine::ui.show')),
        ];
    }
    public function search(): array
    {
        return [
            'user.name',
            'user.iin'
        ];
    }

    public function rules(Model $item): array
    {
        return [
            'user_id' => [
                'required',
                Rule::unique('organization_readers', 'user_id')->where('organization_id', session('selected_admin')?->organization_id)
            ]
        ];
    }

    public function validationMessages(): array
    {
        return [
            'user_id.unique' => __('moonshine::validation.user_id_unique'),
        ];
    }

    protected function resolveRoutes(): void
    {
        parent::resolveRoutes();

        Route::get('/users/search', [UserController::class, 'search'])->name('users.search');
    }
}
