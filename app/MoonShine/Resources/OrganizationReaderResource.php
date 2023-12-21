<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\OrganizationReader;
use App\MoonShine\Controllers\UserController;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use MoonShine\Enums\PageType;
use MoonShine\Fields\Hidden;
use MoonShine\Fields\ID;
use MoonShine\Fields\Preview;
use MoonShine\Fields\Relationships\BelongsTo;

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
                Hidden::make(column: 'organization_id')->setValue(session('selected_admin')?->organization_id)->hideOnIndex()->hideOnDetail(),
                BelongsTo::make(__('moonshine::ui.resource.user_fullname'), 'user', fn($item) => $item->name. ' ('.$item->iin.')', new UserResource())
                    ->hideOnIndex()
                    ->asyncSearch(
                        url: route('moonshine.users.search', [$this->uriKey()])
                    )
            ]),
        ];
    }

    public function rules(Model $item): array
    {
        return [
            'user_id' => [
                'required',
                Rule::unique('organization_readers', 'user_id')->where('organization_id', request('organization_id'))
            ]
        ];
    }

    /**
     * Get custom messages for validator errors
     *
     * @return array<string, string|array<string, string>>
     */
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
