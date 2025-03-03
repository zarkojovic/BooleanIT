<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// fallback route
Route::fallback(function() {
    return response()->json(["message" => "Page Not Found"], 404);
});

Route::prefix('products')->group(function() {
    Route::get("/", [ProductController::class, "index"]);
    Route::get("/category/{category}", [ProductController::class, "getByCategory"]);
    Route::put("/{product}", [ProductController::class, "update"]);
    Route::delete("/{product}", [ProductController::class, "destroy"]);
});

Route::prefix('categories')->group(function() {
    Route::get("/", [CategoryController::class, "index"]);
    Route::put("/{category}", [CategoryController::class, "update"]);
    Route::delete("/{category}", [CategoryController::class, "destroy"]);
    Route::get("/{category}/export", [CategoryController::class, "export"]);
});
