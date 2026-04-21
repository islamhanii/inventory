<?php

namespace App\Http\Requests\Api\Products;

use App\Http\Helpers\ApiResponse;
use App\Http\Helpers\CustomFailedValidation;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    use ApiResponse, CustomFailedValidation;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'sku' => 'required|string|unique:products,sku,' . $this->id . '|max:255',
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'price' => 'required|numeric|between:0,999999.99',
            'stock_quantity' => 'required|integer|between:0,999999',
            'low_stock_threshold' => 'required|integer|between:0,999999',
            'status' => 'required|in:active,inactive,discontinued'
        ];
    }
}
