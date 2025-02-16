<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    public function products()
    {
        return $this->hasMany(Product::class, 'department_id', 'id');
    }
}
