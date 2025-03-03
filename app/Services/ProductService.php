<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;

class ProductService {

    public function getAllProducts() {
        return Product::all()->load('category');
    }

    public function getProductsByCategory(Category $category) {
        try {
            $products = Product::where("category_id", $category->id)->get();
            return ['data' => $products, 'status' => 200];
        } catch (\Exception $e) {
            return ['error' => 'An error occurred while fetching products.', 'status' => 500];
        }
    }
    public function updateProduct(Product $product, $data) {
        $product->update($data);
        return ['data' => $product, 'status' => 200];
    }

    public function deleteProduct(Product $product) {
        try {
            $product->delete();
            return ['message' => 'Product deleted successfully.', 'status' => 200];
        } catch (\Exception $e) {
            return ['error' => 'An error occurred while deleting the product.', 'status' => 500];
        }
    }
}
