<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller {

    /**
     * Display a listing of the resource.
     */
    public function index() {
        $products = Product::all()->load('category');
        return response()->json($products);
    }

    /**
     * Display a listing of the resource by category.
     */
    public function getByCategory(string $id) {
        // Validate the category ID
        if (!is_numeric($id)) {
            return response()->json(['error' => 'Invalid category ID.'], 400);
        }

        // Check if the category exists
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['error' => 'Category not found.'], 404);
        }

        try {
            // Fetch products by category ID
            $products = Product::where("category_id", $id)->get();
            return response()->json($products);
        } catch (\Exception $e) {
            // Handle potential database errors
            return response()->json(['error' => 'An error occurred while fetching products.'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, string $id) {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['error' => 'Product not found.'], 404);
        }
        $product->update($request->all());

        return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {

        $product = Product::find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found.'], 404);
        }

        try {
            $product->delete();
            return response()->json(['message' => 'Product deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while deleting the product.'], 500);
        }

    }

}
