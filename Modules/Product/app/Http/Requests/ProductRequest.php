<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            return [
                'name' => ['required', 'string', 'max:255', 'unique:products,name,' . $this->route('product')?->id],
                'description' => ['sometime', 'string'],
                'price' => ['sometimes', 'numeric', 'min:0'],
                'stock' => ['sometimes', 'numeric', 'min:0'],
                'images.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            ];
        }
        return [
            'name' => ['required', 'string', 'max:255', 'unique:products,name'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['nullable', 'numeric', 'min:0'],
            'images.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $action = in_array($this->method(), ['PUT', 'PATCH']) ? 'update' : 'create';

        return auth()->user()->can($action . ' products');
    }
}
