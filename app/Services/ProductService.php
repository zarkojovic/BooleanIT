<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;

class ProductService {

    public function getAllProducts() {
        return Product::all()->load('category');
    }

    public function getProductsByCategory($id) {
        if (!is_numeric($id)) {
            return ['error' => 'Invalid category ID.', 'status' => 400];
        }

        $category = Category::find($id);
        if (!$category) {
            return ['error' => 'Category not found.', 'status' => 404];
        }

        try {
            $products = Product::where("category_id", $id)->get();
            return ['data' => $products, 'status' => 200];
        } catch (\Exception $e) {
            return ['error' => 'An error occurred while fetching products.', 'status' => 500];
        }
    }

    public function updateProduct($id, $data) {
        $product = Product::find($id);
        if (!$product) {
            return ['error' => 'Product not found.', 'status' => 404];
        }

        $product->update($data);
        return ['data' => $product, 'status' => 200];
    }

    public function deleteProduct($id) {
        $product = Product::find($id);
        if (!$product) {
            return ['error' => 'Product not found.', 'status' => 404];
        }

        try {
            $product->delete();
            return ['message' => 'Product deleted successfully.', 'status' => 200];
        } catch (\Exception $e) {
            return ['error' => 'An error occurred while deleting the product.', 'status' => 500];
        }
    }
}
