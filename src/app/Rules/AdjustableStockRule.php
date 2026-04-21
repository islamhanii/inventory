<?php

namespace App\Rules;

use App\Models\Product;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class AdjustableStockRule implements ValidationRule
{

    public function __construct(protected string $productId) {}

    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $product = Product::find($this->productId);
        $newStockQuantity = $product->stock_quantity + $value;
        if ($newStockQuantity < 0) {
            $fail('The stock quantity must be greater than or equal to zero.');
        } else if ($newStockQuantity > 999999) {
            $fail('The stock quantity must be less than or equal to 999,999.');
        }
    }
}
