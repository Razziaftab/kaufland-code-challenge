<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['entity_id', 'category_name', 'sku', 'name', 'description', 'short_description', 'price',
        'link', 'image', 'brand', 'rating', 'caffeine_type', 'count', 'flavored', 'seasonal', 'in_stock', 'facebook',
        'is_kcup'];
    
}
