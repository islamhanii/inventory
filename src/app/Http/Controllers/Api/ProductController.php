<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Requests\Api\Products\AdjustProductStockRequest;
use App\Http\Requests\Api\Products\CreateProductRequest;
use App\Http\Requests\Api\Products\DeleteProductRequest;
use App\Http\Requests\Api\Products\UpdateProductRequest;
use App\Http\Resources\Api\ProductResource;
use App\Services\ProductService;

class ProductController extends Controller
{
    use ApiResponse;

    public function __construct(private ProductService $productService) {}

    /*-----------------------------------------------------------------------------------------*/

    public function index()
    {
        $products = $this->productService->getPaginatedProducts(request('page', 1), request('per_page', 10));

        return $this->apiResponse(200, null, ProductResource::collection($products), [
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'next_page_url' => $products->nextPageUrl(),
                'prev_page_url' => $products->previousPageUrl()
            ]
        ]);
    }

    /*-----------------------------------------------------------------------------------------*/

    public function show($id)
    {
        return $this->apiResponse(200, null, new ProductResource($this->productService->getProductById($id)));
    }

    /*-----------------------------------------------------------------------------------------*/

    public function store(CreateProductRequest $request)
    {
        $product = $this->productService->createProduct($request->validated());

        return $this->apiResponse(201, null, new ProductResource($product));
    }

    /*-----------------------------------------------------------------------------------------*/

    public function update(UpdateProductRequest $request)
    {
        $product = $this->productService->updateProduct($request->id, $request->safe()->except('id'));

        return $this->apiResponse(200, null, new ProductResource($product));
    }

    /*-----------------------------------------------------------------------------------------*/

    public function destroy(DeleteProductRequest $request)
    {
        $this->productService->deleteProduct($request->id);

        return $this->apiResponse(204);
    }

    /*-----------------------------------------------------------------------------------------*/

    public function adjustStock(AdjustProductStockRequest $request)
    {
        $product = $this->productService->adjustStock($request->id, $request->quantity);

        return $this->apiResponse(200, null, new ProductResource($product));
    }

    /*-----------------------------------------------------------------------------------------*/

    public function lowStock()
    {
        $products = $this->productService->getLowStockProducts();

        return $this->apiResponse(200, null, ProductResource::collection($products));
    }
}
