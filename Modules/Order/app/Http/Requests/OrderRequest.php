<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Modules\Order\Enums\PaymentMethods;
use Modules\Order\Models\Order;
use Modules\Product\Models\Product;

class OrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            return [
                'shipping_address_line_1' => ['required', 'string', 'max:255'],
                'shipping_address_line_2' => ['sometimes', 'nullable', 'string', 'max:255'],
                'shipping_city' => ['required', 'string', 'max:255'],
                'shipping_state' => ['required', 'string', 'max:255'],
                'shipping_postal_code' => ['required', 'digits:4'],
                'billing_address_line_1' => ['sometimes', 'string', 'max:255'],
                'billing_address_line_2' => ['sometimes', 'string', 'max:255'],
                'billing_city' => ['required_with:billing_address_1', 'string', 'max:255'],
                'billing_state' => ['required_with:billing_city', 'string', 'max:255'],
                'billing_postal_code' => ['required_with:billing_state', 'digits:4'],
                'note' => ['sometimes', 'string', 'max:255'],
                'products' => ['sometimes', 'array'],
                'products.*.product_id' => ['required_with:products', 'exists:products,id', 'distinct'],
                'products.*.quantity' => ['required_with:products', 'numeric', 'min:1'],
            ];
        }
        return [
            'payment_method' => ['required', Rule::enum(PaymentMethods::class)],
            'shipping_address_line_1' => ['required', 'string', 'max:255'],
            'shipping_address_line_2' => ['sometimes', 'nullable', 'string', 'max:255'],
            'shipping_city' => ['required_with:shipping_address_1', 'string', 'max:255'],
            'shipping_state' => ['required_with:shipping_city', 'string', 'max:255'],
            'shipping_postal_code' => ['required_with:shipping_state', 'digits:4'],
            'billing_address_1' => ['sometimes', 'string', 'max:255'],
            'billing_address_2' => ['sometimes', 'string', 'max:255'],
            'billing_city' => ['required_with:billing_address_1', 'string', 'max:255'],
            'billing_state' => ['required_with:billing_city', 'string', 'max:255'],
            'billing_postal_code' => ['required_with:billing_state', 'digits:4'],
            'note' => ['sometimes', 'string', 'max:255'],
            'products' => ['required', 'array', 'min:1'],
            'products.*.product_id' => ['required', 'exists:products,id', 'distinct'],
            'products.*.quantity' => ['required', 'numeric', 'min:1'],
        ];
    }


    protected function prepareForValidation()
    {
        $this->merge([
            'products' => collect($this->products)->map(function ($product) {
                $productData = Product::find(Arr::get($product, 'product_id', 0));
                return [
                    'product_id' => $productData->id ?? null,
                    'quantity' => Arr::get($product, 'quantity', 0),
                    'stock' => $productData?->stock,
                ];
            })->toArray(),
        ]);
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            foreach ($this->products as $product) {

                if (!$product['product_id']) {
                    $validator->errors()->add('products.' . $product['product_id'], 'Invalid product id.');
                }
                if ($product['quantity'] <= 0 && $product['product_id']) {
                    $validator->errors()->add('products.' . $product['product_id'], 'Quantity must be at least 1.');
                }
                if ($product['quantity'] > $product['stock'] && $product['product_id']) {
                    $validator->errors()->add('products.' . $product['product_id'], 'Quantity exceeds available stock.');
                }
            }
        });
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $action = in_array($this->method(), ['PUT', 'PATCH']) ? 'update' : 'create';
        return $this->user()->can($action . ' orders');
    }
}
