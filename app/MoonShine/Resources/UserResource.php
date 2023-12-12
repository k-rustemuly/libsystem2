<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Grid;
use MoonShine\Fields\Email;
use MoonShine\Fields\ID;
use MoonShine\Fields\Password;
use MoonShine\Fields\PasswordRepeat;
use MoonShine\Fields\Text;

class UserResource extends ModelResource
{
    protected string $model = User::class;

    public function title(): string
    {
        return __('moonshine::ui.resource.users');
    }

    public function getActiveActions(): array
    {
        return ['create', 'view', 'update'];
    }

    public function fields(): array
    {
        return [
            Grid::make([
                Column::make([
                    Block::make(__('moonshine::ui.resource.contact_information'), [
                        ID::make()->sortable(),
                        Text::make(__('moonshine::ui.resource.user_iin'), 'iin')
                            ->required(),
                        Text::make(__('moonshine::ui.resource.user_fullname'), 'name')
                            ->required(),
                        Email::make('E-mail', 'email'),
                        Text::make(__('moonshine::ui.phone_number'), 'phone_number_formatted')
                            ->mask('+7 (999) 999-99-99')
                            ->hint('+7 (___) ___-__-__'),
                    ]),
                    Block::make([
                        Password::make(__('moonshine::ui.resource.password'), 'password')
                            ->customAttributes(['autocomplete' => 'new-password'])
                            ->hideOnIndex(),

                        PasswordRepeat::make(__('moonshine::ui.resource.repeat_password'), 'password_repeat')
                            ->customAttributes(['autocomplete' => 'confirm-password'])
                            ->hideOnIndex(),
                    ]),
                ]),
            ]),
        ];
    }

    public function rules(Model $item): array
    {
        return [
            'name' => 'required',
            'phone_number_formatted' => 'sometimes|required',
            'email' => 'sometimes|bail|required|email|unique:users,email' . ($item->exists ? ",$item->id" : ''),
            'iin' => 'sometimes|required|size:12|unique:users,iin' . ($item->exists ? ",$item->id" : ''),
            'password' => ! $item->exists
                ? 'required|min:6|required_with:password_repeat|same:password_repeat'
                : 'sometimes|nullable|min:6|required_with:password_repeat|same:password_repeat',
        ];
    }

    public function search(): array
    {
        return [
            'id',
            'iin',
            'name',
            'email',
            'phone_number'
        ];
    }
}
