<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller {

    /**
     * Display a listing of the resource.
     */
    public function index() {
        $categories = Category::all();
        return response()->json($categories);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, string $id) {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['error' => 'Category not found.'], 404);
        }

        $category->name = $request->name;

        try {
            $category->save();
            return response()->json(['message' => 'Category updated successfully.']);
        }
        catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the category.'],
                500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['error' => 'Category not found.'], 404);
        }

        if ($category->products->count() > 0) {
            return response()->json(['error' => 'Category cannot be deleted because it has products.'],
                400);
        }

        try {
            $category->delete();
            return response()->json(['message' => 'Category deleted successfully.']);
        }
        catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while deleting the category.'],
                500);
        }
    }

    /**
     * Export the specified resource from storage.
     */
    public function export(string $id) {
        $category = Category::findOrFail($id);
        $products = $category->products;

        // Replace any non-alphanumeric characters with an underscore
        $categoryName = preg_replace('/[^A-Za-z0-9]/', '_', $category->name);

        // File name with category name and current date and time
        $fileName = $categoryName."_".date("Y_m_d-H_i").".csv";

        // Ensure the exports directory exists
        $exportsDir = public_path("exports");

        if (!file_exists($exportsDir)) {
            mkdir($exportsDir, 0777, TRUE);
        }

        // Save the file in the public folder
        $filePath = $exportsDir."/$fileName";

        $file = fopen($filePath, "w");

        // Write the header
        fputcsv($file, [
            "product_number",
            "category_name",
            "department_name",
            "manufacturer_name",
            "upc",
            "sku",
            "regular_price",
            "sale_price",
            "description",
        ]);

        // Write the products
        foreach ($products as $p) {
            fputcsv($file, [
                $p->product_number,
                $p->category->name,
                $p->department->name,
                $p->manufacturer->name,
                $p->upc,
                $p->sku,
                $p->regular_price,
                $p->sale_price,
                $p->description,
            ]);
        }

        fclose($file);

        return response()->download($filePath)->deleteFileAfterSend();
    }

}
