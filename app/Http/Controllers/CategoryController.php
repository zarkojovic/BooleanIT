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

    public function update(UpdateCategoryRequest $request, string $id) : JsonResponse{
        $result = $this->categoryService->updateCategory($id, $request->all());

        return response()->json(['message' => $result['message']], $result['status']);
    }

    public function destroy(string $id) : JsonResponse{
        $result = $this->categoryService->deleteCategory($id);

        return response()->json(['message' => $result['message']], $result['status']);
    }

    public function export(string $id) {
        return $this->categoryService->exportCategory($id);
    }

}
