<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\OrganizationBookTransaction;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;

class OrganizationBookTransactionResource extends ModelResource
{

    protected string $title = 'OrganizationBookTransactions';

    public function getModel(): Model
    {
        return new OrganizationBookTransaction(['organization_id' => session('selected_admin')?->organization_id]);
    }

    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
            ]),
        ];
    }

    public function rules(Model $item): array
    {
        return [];
    }
}
