<?php

declare(strict_types=1);

namespace App\MoonShine\Layouts;

use MoonShine\Components\Layout\{Content, Flash, Footer, LayoutBlock, LayoutBuilder};
use MoonShine\Contracts\MoonShineLayoutContract;

final class WithoutSideBarLayout implements MoonShineLayoutContract
{
    public static function build(): LayoutBuilder
    {
        return LayoutBuilder::make([
            LayoutBlock::make([
                Flash::make(),
                Content::make(),
                Footer::make(),
            ])->customAttributes(['class' => 'layout-page']),
        ]);
    }
}
