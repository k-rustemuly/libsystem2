<?php

declare(strict_types=1);

namespace App\MoonShine\Fields;

use MoonShine\Fields\Field;
use Illuminate\Contracts\View\View;

class Image extends Field
{
    protected string $view = 'fields.image';

    protected function resolvePreview(): View|string
    {
        return view(
            $this->view,
            ['image' => value($this->formattedValueCallback())]
        );
    }

}
