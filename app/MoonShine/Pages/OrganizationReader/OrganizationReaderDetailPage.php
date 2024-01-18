<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\OrganizationReader;

use MoonShine\Buttons\CreateButton;
use MoonShine\Components\ActionGroup;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Flex;
use MoonShine\Decorations\Grid;
use MoonShine\Pages\Crud\DetailPage;

class OrganizationReaderDetailPage extends DetailPage
{
    protected function bottomLayer(): array
    {
        return [
            ...parent::bottomLayer(),
            Grid::make([
                Column::make([
                    Flex::make([
                        ActionGroup::make([
                            //CreateButton::for($this->getResource(), 'index-table'),
                        ]),
                    ])->justifyAlign('start'),

                ])->customAttributes([
                    'class' => 'flex flex-wrap items-center justify-between gap-2 sm:flex-nowrap',
                ]),
            ]),
        ];
    }
}
