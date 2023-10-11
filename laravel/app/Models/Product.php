<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public const SUB_END_POINT = '/admin/api/';

    public const PRODUCT_END_POINT = 'products.json';

    const TYPE_SYNC = [
        'AUTO' => 1,
        'MANUAL' => 2
    ];

    protected $table = 'products';

    protected $fillable = [
        'title', 'body_html', 'vendor', 'images',
        'shopify_product_id', 'product_type', 'type_sync', 'time_sync', 'user_id'
    ];
}
