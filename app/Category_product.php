<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category_product extends Model
{
    protected $guarded = ['category_id', 'product_id'];
}
