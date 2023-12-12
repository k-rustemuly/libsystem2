<?php

declare(strict_types=1);

namespace App\MoonShine\Card;

use MoonShine\ActionButtons\ActionButtons;
use MoonShine\Fields\Field;
use MoonShine\Fields\Fields;
use MoonShine\Traits\Makeable;

final class CardItem
{
    use Makeable;

    protected ?Field $badge = null;

    protected ?Field $thumbnail = null;

    protected ?Field $title = null;

    protected ?Field $subTitle = null;

    public function __construct(
        protected mixed $data,
        protected Fields $fields,
        protected ActionButtons $actions,
        array $properties,
    ) {
        foreach($properties as $type => $column) {
            $index = -1;
            $field = $fields->first(function (Field $field, $i) use($column, &$index) {
                $index = $i;
                return $field->column() === $column;
            });
            if($field) {
                $this->{$type} = $field;
                $fields->forget($index);
            }
        }
    }

    public function getBadge(): ?Field
    {
        return $this->badge;
    }

    public function getThumbnail(): ?Field
    {
        return $this->thumbnail;
    }

    public function getTitle(): ?Field
    {
        return $this->title;
    }

    public function getSubTitle(): ?Field
    {
        return $this->subTitle;
    }

    public function getFields(): Fields
    {
        return $this->fields;
    }

    public function getActions(): ActionButtons
    {
        return $this->actions;
    }

}
