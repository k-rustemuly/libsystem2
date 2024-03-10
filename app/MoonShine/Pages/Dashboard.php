<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use App\Models\BookStorageType;
use App\Models\Role;
use Carbon\Carbon;
use MoonShine\Decorations\Grid;
use MoonShine\Metrics\DonutChartMetric;
use MoonShine\Metrics\LineChartMetric;
use MoonShine\Metrics\ValueMetric;
use MoonShine\Pages\Page;

class Dashboard extends Page
{
    public function breadcrumbs(): array
    {
        return [
            '#' => $this->title()
        ];
    }

    public function title(): string
    {
        return $this->title ?: 'Dashboard';
    }

    public function components(): array
	{
        if(session('selected_admin')->role_id == Role::LIBRARIAN) {
            $admin = session('selected_admin');
            $currentMonth = (int) Carbon::now()->format('m');
            $months = __('moonshine::ui.months');
            $monthsBeforeCurrent = array_slice($months, 0, $currentMonth, true);
            return [
                Grid::make([
                    ValueMetric::make(__('moonshine::ui.resource.books'))
                        ->value($admin->organization->books()->count())
                        ->columnSpan(4),

                    ValueMetric::make(__('moonshine::ui.resource.all-books'))
                        ->value($admin->organization->books()->sum('count'))
                        ->columnSpan(4),

                    ValueMetric::make(__('moonshine::ui.resource.readers'))
                        ->value($admin->organization->readers()->count())
                        ->columnSpan(4),

                    DonutChartMetric::make(__('moonshine::ui.resource.books'))
                        ->values(BookStorageType::all()->map(function($type) use($admin) {
                            return [
                                $type->name => $admin->organization->books()->where('book_storage_type_id', $type->id)->count()
                            ];
                        })->flatMap(function ($item) {
                            return $item;
                        })->toArray())
                        ->columnSpan(6),
                    DonutChartMetric::make(__('moonshine::ui.resource.all-books'))
                        ->values(BookStorageType::all()->map(function($type) use($admin) {
                            return [
                                $type->name => (int) $admin->organization->books()->where('book_storage_type_id', $type->id)->sum('count')
                            ];
                        })->flatMap(function ($item) {
                            return $item;
                        })->toArray())
                        ->columnSpan(6),

                    LineChartMetric::make(__('moonshine::ui.resource.inputs'))
                        ->line([
                            __('moonshine::ui.resource.reader') => $monthsBeforeCurrent
                        ]),
                ])
            ];
        }
		return [];
	}
}
