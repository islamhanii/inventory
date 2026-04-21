<?php

namespace App\Http\Requests\Api\Products;

use App\Http\Helpers\ApiResponse;
use App\Http\Helpers\CustomFailedValidation;
use App\Rules\AdjustableStockRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AdjustProductStockRequest extends FormRequest
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
            'quantity' => [
                'required',
                'integer',
                'between:-999999,999999',
                'not_in:0',
                new AdjustableStockRule($this->id, $this->quantity)
            ]
        ];
    }
}
