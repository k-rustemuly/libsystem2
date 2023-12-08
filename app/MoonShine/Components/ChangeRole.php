<?php

declare(strict_types=1);

namespace App\MoonShine\Components;

use App\Models\Admin;
use App\MoonShine\Pages\ChooseRole;
use MoonShine\Components\MoonShineComponent;

/**
 * @method static static make()
 */
final class ChangeRole extends MoonShineComponent
{
    protected string $view = 'admin.components.change-role';

    public Admin $admin;

    public function __construct()
    {
        $this->admin = session('selected_admin');
    }

    protected function viewData(): array
    {
        return [
            'route' => '#',
            'toRoute' => to_page(ChooseRole::class),
            'avatar' => $this->admin->organization->image,
            'nameOfUser' => $this->admin->organization->short_name,
            'username' => $this->admin->role->name,
        ];
    }
}
