<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\OrganizationBookInventory;

use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\Hidden;
use MoonShine\Fields\ID;

class OrganizationBookInventoryResource extends ModelResource
{
    protected string $model = OrganizationBookInventory::class;

    protected string $title = 'OrganizationBookInventories';

    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
                Hidden::make(__('moonshine::ui.resource.barcode'), 'code'),
        ]),
        ];
    }

    public function rules(Model $item): array
    {
        return [];
    }
}
