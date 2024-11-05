<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Modules\Order\Enums\PaymentMethods;
use Modules\Order\Models\Order;
use Modules\Product\Models\Product;

class BatchOrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'orders' => ['required', 'min:1', 'array'],
            'orders.*' => ['array']
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $orders = $this->input('orders');

            foreach ($orders as $key => $order) {
                $orderValidator = Validator::make($order, (new OrderRequest())->rules());

                if ($orderValidator->fails()) {
                    foreach ($orderValidator->errors()->all() as $error) {
                        $validator->errors()->add("orders.$key.$error", $error);
                    }
                }
            }
        });
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create orders');
    }
}
