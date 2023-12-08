<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use App\Models\User;
use App\MoonShine\Components\Card;
use App\MoonShine\Components\Profile;
use MoonShine\ActionButtons\ActionButton;
use MoonShine\Decorations\Divider;
use MoonShine\MoonShineAuth;
use MoonShine\Pages\Page;

class ChooseRole extends Page
{
    protected string $layout = 'layouts.chooseRole';

    public function breadcrumbs(): array
    {
        return [];
    }

    public function components(): array
	{
		return [
            Profile::make(),
            Divider::make(__('moonshine::ui.resource.choose_role'))->centered(),
            Card::make(MoonShineAuth::guard()->user(), 'admins')
                ->with(['organization', 'role'])
                ->columnSpan(3)
                ->badge('role.name')
                ->thumbnail('organization.image')
                ->title('organization.short_name')
                ->subTitle('organization.short_type')
                ->overlay()
                ->actions(
                    function($item) {
                        return [
                            ActionButton::make(__('moonshine::ui.resource.choose'), route('admin.choose.to', $item->id))
                        ];
                    }
                )
        ];
	}


}
