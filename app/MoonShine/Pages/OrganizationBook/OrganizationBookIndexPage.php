<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\OrganizationBook;

use App\Models\Admin;
use App\MoonShine\Components\Cards;
use MoonShine\Buttons\DeleteButton;
use MoonShine\Buttons\DetailButton;
use MoonShine\Buttons\EditButton;
use MoonShine\Decorations\Fragment;
use MoonShine\Fields\Hidden;
use MoonShine\Fields\Preview;
use MoonShine\Fields\Image;
use MoonShine\MoonShineAuth;
use MoonShine\Pages\Crud\IndexPage;
use MoonShine\TypeCasts\ModelCast;

class OrganizationBookIndexPage extends IndexPage
{
    public function fields(): array
    {
        return [
            Image::make('organization.image'),
            Preview::make('role.name')->badge('success'),
            Hidden::make('organization.short_name'),
            Hidden::make('organization.short_type'),
            Hidden::make('Kato', 'organization.kato')
        ];
    }

    protected function topLayer(): array
    {
        return [
            ...parent::topLayer()
        ];
    }

    protected function mainLayer(): array
    {
        return [...$this->filtersForm(), ...$this->actionButtons(), ...$this->queryTags(), ...$this->cards()];
    }

    protected function cards(): array
    {
        $cardName = 'index-card';

        // $items = $this->getResource()->isPaginationUsed()
        //     ? $this->getResource()->paginate()
        //     : $this->getResource()->items();

        return [
            Fragment::make([
                Cards::make(items: MoonShineAuth::guard()->user()->admins()->get())
                    ->name($cardName)
                    ->fields(fn () => $this->getResource()->getIndexFields()->toArray())
                    ->cast(ModelCast::make(Admin::class))
                    ->badge('role.name')
                    ->thumbnail('organization.image')
                    ->title('organization.short_name')
                    ->subTitle('organization.short_type')
                    ->columnSpan(4)
                    ->overlay(true)
                    ->buttons([
                        ...$this->getResource()->getIndexButtons(),
                        DetailButton::for($this->getResource()),
                        EditButton::for($this->getResource(), $cardName),
                        DeleteButton::for($this->getResource(), $cardName),
                    ]),
            ])->name('crud-card'),
        ];
    }

    protected function bottomLayer(): array
    {
        return [
            ...parent::bottomLayer()
        ];
    }
}
