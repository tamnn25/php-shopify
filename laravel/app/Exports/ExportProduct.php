<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportProduct implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Product::all();
    }

    public function headings(): array
    {

        return [
            'stt',
            'title',
            'body_html',
            'vendor',
            'images',
            'shopify_product_id',
            'product_type',
            'type_sync',
            'time_sync',
            'user_id',
            'created_at',
            'updated_at'
        ];

    }
}
