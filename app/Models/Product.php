<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model {

    protected $fillable = [
        'product_number',
        'regular_price',
        'sale_price',
        'description',
        'category_id',
        'department_id',
        'manufacturer_id',
        'upc',
        'sku',
    ];

    protected $casts = [
        'regular_price' => 'float',
        'sale_price' => 'float',
    ];

    public function category() {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function department() {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function manufacturer() {
        return $this->belongsTo(Manufacturer::class, 'manufacturer_id', 'id');
    }

}
