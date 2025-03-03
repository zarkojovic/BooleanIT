<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller {

    protected $productService;

    public function __construct(ProductService $productService) {
        $this->productService = $productService;
    }

    public function index(): JsonResponse {
        $products = $this->productService->getAllProducts();
        return response()->json($products);
    }

    public function getByCategory(Category $category): JsonResponse {
        $result = $this->productService->getProductsByCategory($category);

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], $result['status']);
        }

        return response()->json($result['data'], $result['status']);
    }

    public function update(UpdateProductRequest $request, Product $product): JsonResponse {
        $result = $this->productService->updateProduct($product, $request->all());

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], $result['status']);
        }

        return response()->json($result['data'], $result['status']);
    }

    public function destroy(Product $product): JsonResponse {
        $result = $this->productService->deleteProduct($product);

        return response()->json(['message' => $result['message']], $result['status']);
    }
}
