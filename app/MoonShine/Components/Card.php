<?php

declare(strict_types=1);

namespace App\MoonShine\Components;

use Closure;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Components\MoonShineComponent;
use MoonShine\Traits\WithColumnSpan;
use Illuminate\Contracts\Database\Eloquent\Builder;

/**
 * @method static static make()
 */
final class Card extends MoonShineComponent
{
    use WithColumnSpan;

    protected string $view = 'admin.components.card';

    protected bool $overlay = false;

    protected array $with = [];

    protected ?Builder $query = null;

    protected ?string $relationName = null;

    protected string|Closure $title = 'title';

    protected string|Closure $subTitle = 'subTitle';

    protected string|Closure $thumbnail = 'thumbnail';

    protected string|Closure $badge = 'badge';

    protected Closure $actions;

    public function __construct(protected Model $model, protected string|Closure $relationNameOrQuery)
    {
        if(is_string($relationNameOrQuery)) {
            $this->relationName = $relationNameOrQuery;
        }
    }

    protected function viewData(): array
    {
        return [
            'element' => $this
        ];
    }

    public function items()
    {
        return $this->getModel()
            ->{$this->getRelationName()}()
            ->with($this->getWith())
            ->get();
    }

    public function query(): Builder
    {
        if (! is_null($this->query)) {
            return $this->query;
        }

        $this->query = $this->getModel()->query();

        if ($this->hasWith()) {
            $this->query->with($this->getWith());
        }

        return $this->query;
    }

    public function badge(string|Closure $badge): static
    {
        $this->badge = $badge;
        return $this;
    }

    public function actions(Closure $actions): static
    {
        $this->actions = $actions;
        return $this;
    }

    public function getActions($item)
    {
        return value($this->actions, $item);
    }

    public function getBadge($item)
    {
        if(is_closure($this->badge)) {
            return value($this->badge, $item);
        }
        return data_get($item, $this->badge);
    }

    public function subTitle(string|Closure $subTitle): static
    {
        $this->subTitle = $subTitle;
        return $this;
    }

    public function getSubTitle($item)
    {
        if(is_closure($this->subTitle)) {
            return value($this->subTitle, $item);
        }
        return data_get($item, $this->subTitle);
    }

    public function thumbnail(string|Closure $thumbnail): static
    {
        $this->thumbnail = $thumbnail;
        return $this;
    }

    public function getThumbnail($item)
    {
        if(is_closure($this->thumbnail)) {
            return value($this->thumbnail, $item);
        }
        return data_get($item, $this->thumbnail);
    }

    public function title(string|Closure $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function getTitle($item)
    {
        if(is_closure($this->title)) {
            return value($this->title, $item);
        }
        return data_get($item, $this->title);
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

    public function getRelationName(): ?string
    {
        return $this->relationName;
    }

    public function with(array $with): static
    {
        $this->with = $with;

        return $this;
    }

    public function hasWith(): bool
    {
        return $this->with !== [];
    }

    public function getWith(): array
    {
        return $this->with;
    }

    public function getModel(): Model
    {
        return $this->model;
    }
}
