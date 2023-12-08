<?php

declare(strict_types=1);

namespace App\MoonShine\Controllers;

use App\Models\Admin;
use App\MoonShine\Pages\Dashboard;
use MoonShine\MoonShineRequest;
use MoonShine\Http\Controllers\MoonShineController;
use MoonShine\MoonShineAuth;
use Symfony\Component\HttpFoundation\Response;

final class OrganizationChooseController extends MoonShineController
{
    public function to(Admin $admin, MoonShineRequest $request): Response
    {
        if($admin->user_id == MoonShineAuth::guard()->id() && $admin->is_active) {
            session(['selected_admin' => $admin]);
            return to_page(page: Dashboard::class, redirect: true);
        }

        $this->toast(__('moonshine::ui.access_denied'), 'error');
        return back();
    }
}
