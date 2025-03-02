<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class CategoryService {

    public function getAllCategories() {
        return Category::all();
    }

    public function updateCategory($id, $data) {
        $category = Category::find($id);

        if (!$category) {
            return ['error' => 'Category not found.', 'status' => 404];
        }

        $category->name = $data['name'];

        try {
            $category->save();
            return ['message' => 'Category updated successfully.', 'status' => 200];
        } catch (\Exception $e) {
            return ['error' => 'An error occurred while updating the category.', 'status' => 500];
        }
    }

    public function deleteCategory($id) {
        $category = Category::find($id);

        if (!$category) {
            return ['error' => 'Category not found.', 'status' => 404];
        }

        if ($category->products->count() > 0) {
            return ['error' => 'Category cannot be deleted because it has products.', 'status' => 400];
        }

        try {
            $category->delete();
            return ['message' => 'Category deleted successfully.', 'status' => 200];
        } catch (\Exception $e) {
            return ['error' => 'An error occurred while deleting the category.', 'status' => 500];
        }
    }

    public function exportCategory($id) {
        $category = Category::findOrFail($id);
        $products = $category->products;

        $categoryName = preg_replace('/[^A-Za-z0-9]/', '_', $category->name);
        $fileName = $categoryName . "_" . date("Y_m_d-H_i") . ".csv";
        $exportsDir = public_path("exports");

        if (!file_exists($exportsDir)) {
            mkdir($exportsDir, 0777, true);
        }

        $filePath = $exportsDir . "/$fileName";
        $file = fopen($filePath, "w");

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
