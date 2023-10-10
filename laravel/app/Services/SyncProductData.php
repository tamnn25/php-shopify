<?php

namespace App\Services;

use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;

class SyncProductData
{
    public static function syncProduct($auto=False)
    {
        $user = User::first();
        $date = Carbon::createFromFormat('Y-m-d H:i:s', $user->created_at)->format('Y-m');
        $url = Product::SUB_END_POINT . $date . '/' . Product::PRODUCT_END_POINT;

        $response = $user->api()->rest('GET', $url);
        $products = $response['body']->container['products'];
        
        foreach ($products as $product) { 
            if (!Product::where('shopify_product_id',$product['id'])->exists()) {
                Product::create([
                    'title' => $product['title'],
                    'body_html' => $product['body_html'] ? substr($product['body_html'], 0, 201)  : '',
                    'vendor' => $product['vendor'],
                    'shopify_product_id' => $product['id'],
                    'product_type' => $product['product_type'],
                    'type_sync' => $auto ? Product::TYPE_SYNC['AUTO'] : Product::TYPE_SYNC['MANUAL'],
                    'time_sync' => Carbon::now(),
                    'user_id' => $user->id
                ]);
            }
        }

    }
}
