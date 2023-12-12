<?php

declare(strict_types=1);

namespace App\MoonShine\Components;

use App\MoonShine\Card\CardItem;
use Closure;
use MoonShine\Components\IterableComponent;
use MoonShine\Fields\Fields;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use MoonShine\Fields\Field;
use MoonShine\Traits\WithColumnSpan;

/**
 * @method static static make(Fields|array $fields = [], Paginator|iterable $items = [], ?Paginator $paginator = null)
 */
final class Cards extends IterableComponent
{
    use WithColumnSpan;

    protected string $view = 'admin.components.cards';

    protected bool $overlay = false;

    protected string $title = 'title';

    protected string $subTitle = 'subTitle';

    protected string $thumbnail = 'thumbnail';

    protected string $badge = 'badge';

    protected bool $isSimple = false;

    public function __construct(
        Fields|array $fields = [],
        Paginator|iterable $items = [],
        ?Paginator $paginator = null
    ) {
        $this->fields($fields);
        $this->items($items);

        if (! is_null($paginator)) {
            $this->paginator($paginator);
        }
    }

    public function getItems(): Collection
    {
        return collect($this->items);
    }

    /**
     * @throws Throwable
     */
    protected function getFilledFields(array $raw = [], mixed $casted = null, int $index = 0): Fields
    {
        $fields = $this->getFields();

        if (is_closure($this->fieldsClosure)) {
            $fields->fill($raw, $casted, $index);

            return $fields;
        }

        return $fields->fillCloned($raw, $casted, $index);
    }

    public function overlay(): static
    {
        $this->overlay = true;

        return $this;
    }

    public function isOverlay(): bool
    {
        return $this->overlay;
    }

    public function badge(string $badge): static
    {
        $this->badge = $badge;

        return $this;
    }

    public function subTitle(string $subTitle): static
    {
        $this->subTitle = $subTitle;

        return $this;
    }

    public function thumbnail(string $thumbnail): static
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    public function title(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function simple(): static
    {
        $this->isSimple = true;

        return $this;
    }

    public function isSimple(): bool
    {
        return $this->isSimple;
    }

    public function getCardItems(): Collection
    {
        $properties = [
            'badge' => $this->badge,
            'thumbnail' => $this->thumbnail,
            'title' => $this->title,
            'subTitle' => $this->subTitle,
        ];
        return $this->getItems()->filter()->map(function (mixed $data, $index) use($properties): CardItem {
            $casted = $this->castData($data);
            $raw = $this->unCastData($data);

            $fields = $this->getFilledFields($raw, $casted, $index);

            if (! is_null($this->getName())) {
                $fields->onlyFields()->each(
                    fn (Field $field): Field => $field->formName($this->getName())
                );
            }

            return CardItem::make(
                $casted,
                $fields,
                $this->getButtons($casted),
                $properties
            );
        });
    }

    protected function viewData(): array
    {
        return [
            'items' => $this->getCardItems(),
            'overlay' => $this->isOverlay(),
            'name' => $this->getName(),
            'hasPaginator' => $this->hasPaginator(),
            'simple' => $this->isSimple(),
            'simplePaginate' => ! $this->getPaginator() instanceof LengthAwarePaginator,
            'paginator' => $this->getPaginator(),
            'columnSpan' => $this->columnSpanValue(),
            'adaptiveColumnSpan' => $this->adaptiveColumnSpanValue(),
        ];
    }
}
