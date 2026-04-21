<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProductService
{
    public function getPaginatedProducts(int $page = 1, int $perPage = 10)
    {
        $products = Cache::remember(
            'products_page_' . $page . '_per_page_' . $perPage,
            60,
            fn() => Product::paginate($perPage)
        );

        return $products;
    }

    /*-----------------------------------------------------------------------------------------*/

    public function getProductById($id)
    {
        return Product::findOrFail($id);
    }

    /*-----------------------------------------------------------------------------------------*/

    public function createProduct(array $data)
    {
        try {
            return DB::transaction(function () use ($data) {
                $product = Product::create($data);

                Cache::flush();

                return $product;
            });
        } catch (Throwable $e) {
            Log::error('Create product failed', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);

            throw $e;
        }
    }

    /*-----------------------------------------------------------------------------------------*/

    public function updateProduct(string $id, array $data)
    {
        try {
            return DB::transaction(function () use ($id, $data) {
                $product = Product::findOrFail($id);

                $product->update($data);

                Cache::flush();

                return $product;
            });
        } catch (Throwable $e) {
            Log::error('Update product failed', [
                'product_id' => $id,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /*-----------------------------------------------------------------------------------------*/

    public function deleteProduct(string $id)
    {
        try {
            return DB::transaction(function () use ($id) {
                $product = Product::findOrFail($id);

                $product->delete();

                Cache::flush();

                return true;
            });
        } catch (Throwable $e) {
            Log::error('Delete product failed', [
                'product_id' => $id,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /*-----------------------------------------------------------------------------------------*/

    public function adjustStock(string $id, int $quantity)
    {
        try {
            return DB::transaction(function () use ($id, $quantity) {
                $product = Product::findOrFail($id);

                $product->stock_quantity += $quantity;
                $product->save();

                Cache::flush();

                return $product;
            });
        } catch (Throwable $e) {
            Log::error('Stock adjustment failed', [
                'product_id' => $id,
                'quantity' => $quantity,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /*-----------------------------------------------------------------------------------------*/

    public function getLowStockProducts()
    {
        return Product::whereColumn(
            'stock_quantity',
            '<=',
            'low_stock_threshold'
        )->get();
    }
}
