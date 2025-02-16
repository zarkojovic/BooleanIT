<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manufacturer extends Model {

    public function products() {
        return $this->hasMany(Product::class, 'manufacturer_id', 'id');
    }

}
