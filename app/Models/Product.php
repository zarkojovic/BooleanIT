<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{



    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class, 'manufacturer_id', 'id');
    }
}
