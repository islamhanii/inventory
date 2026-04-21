<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_all_products(): void
    {
        Product::factory()->count(3)->create();

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
                'meta' => [
                    'pagination' => [
                        'current_page',
                        'last_page',
                        'per_page',
                        'total',
                        'next_page_url',
                        'prev_page_url',
                    ]
                ]
            ]);
    }

    /*-----------------------------------------------------------------------------------------*/

    public function test_can_get_a_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->getJson("/api/products/show/{$product->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data'
            ]);
    }

    /*-----------------------------------------------------------------------------------------*/

    public function test_can_create_a_product(): void
    {
        $response = $this->postJson('/api/products', [
            'sku' => 'SKU-100',
            'name' => 'Test Product',
            'description' => 'Test description',
            'price' => 100,
            'stock_quantity' => 50,
            'low_stock_threshold' => 10,
            'status' => 'active'
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'data'
            ]);
    }

    /*-----------------------------------------------------------------------------------------*/

    public function test_can_update_a_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->putJson("/api/products/{$product->id}", [
            'sku' => $product->sku,
            'name' => 'Updated Name',
            'description' => 'Updated description',
            'price' => 200,
            'stock_quantity' => 30,
            'low_stock_threshold' => 5,
            'status' => 'active'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data'
            ]);
    }

    /*-----------------------------------------------------------------------------------------*/

    public function test_can_delete_a_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(204);
    }

    /*-----------------------------------------------------------------------------------------*/

    public function test_can_adjust_stock(): void
    {
        $product = Product::factory()->create();

        $response = $this->postJson("/api/products/{$product->id}/adjust-stock", [
            'quantity' => 10
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data'
            ]);
    }

    /*-----------------------------------------------------------------------------------------*/

    public function test_can_get_low_stock_products(): void
    {
        Product::factory()->count(3)->create();

        $response = $this->getJson('/api/products/low-stock');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data'
            ]);
    }
}
