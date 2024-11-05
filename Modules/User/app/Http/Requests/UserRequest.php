<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            return [
                'name' => ['nullable', 'string', 'max:255'],
                'email' => ['sometimes', 'unique:users,email,' . $this->route('user')?->id],
                'phone_number' => ['sometimes', 'unique:users,phone_number,' . $this->route('user')?->id],
                'password' => ['sometimes', Password::min(8)->symbols()],
            ];
        }
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'unique:users,email'],
            'phone_number' => ['required', 'numeric', 'digits:8', 'unique:users,phone_number'],
            'password' => ['required', Password::min(8)->symbols()],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $action = in_array($this->method(), ['PUT', 'PATCH']) ? 'update' : 'create';
        return $this->user()->can($action . ' users');
    }
}
