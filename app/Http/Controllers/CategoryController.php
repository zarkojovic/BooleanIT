<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller {

    protected $categoryService;

    public function __construct(CategoryService $categoryService) {
        $this->categoryService = $categoryService;
    }

    public function index(): JsonResponse {
        $categories = $this->categoryService->getAllCategories();
        return response()->json($categories);
    }

    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse {
        $result = $this->categoryService->updateCategory($category, $request->all());

        return response()->json(['message' => $result['message']], $result['status']);
    }

    public function destroy(Category $category): JsonResponse {
        $result = $this->categoryService->deleteCategory($category);

        return response()->json(['message' => $result['message']], $result['status']);
    }

    public function export(Category $category) {
        return $this->categoryService->exportCategory($category);
    }

}
