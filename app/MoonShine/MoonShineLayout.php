<?php

declare(strict_types=1);

namespace App\MoonShine;

use App\MoonShine\Components\ChangeRole;
use MoonShine\Components\Layout\{Content, Flash, Footer, Header, LayoutBlock, LayoutBuilder, Menu, Sidebar};
use MoonShine\Contracts\MoonShineLayoutContract;
use MoonShine\Decorations\Divider;

final class MoonShineLayout implements MoonShineLayoutContract
{
    public static function build(): LayoutBuilder
    {
        return LayoutBuilder::make([
            Sidebar::make([
                Menu::make()->customAttributes(['class' => 'mt-2']),
                Divider::make(),
                ChangeRole::make()
            ]),
            LayoutBlock::make([
                Flash::make(),
                Header::make(),
                Content::make(),
                Footer::make(),
            ])->customAttributes(['class' => 'layout-page']),
        ]);
    }
}
