<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function(Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get("/test", function() {
    return response()->json(["message" => "Hello World"]);
});

// fallback route
Route::fallback(function() {
    return response()->json(["message" => "Page Not Found"], 404);
});

Route::prefix('products')->group(function() {
    Route::get("/", [ProductController::class, "index"]);
    Route::get("/category/{id}", [ProductController::class, "getByCategory"]);
    Route::put("/{id}", [ProductController::class, "update"]);
    Route::delete("/{id}", [ProductController::class, "destroy"]);
});

Route::prefix('categories')->group(function() {
    Route::get("/", [CategoryController::class, "index"]);
    Route::put("/{id}", [CategoryController::class, "update"]);
    Route::delete("/{id}", [CategoryController::class, "destroy"]);
    Route::get("/{id}/export", [CategoryController::class, "export"]);
});

