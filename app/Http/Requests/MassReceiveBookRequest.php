<?php

namespace App\Http\Requests;

use App\Models\Role;
use MoonShine\Http\Requests\MoonShineFormRequest;

class MassReceiveBookRequest extends MoonShineFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return session('selected_admin')->role_id == Role::LIBRARIAN;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'date.received_date' => 'required|date|before_or_equal:today',
            'date.return_date' => 'required|date|after_or_equal:date.received_date',
            'books' => 'required|array'
        ];
    }
}
