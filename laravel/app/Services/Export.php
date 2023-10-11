<?php

namespace App\Services;

use App\Exports\ExportProduct;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Export
{
    public static function exportProductCSVFile(){
        try {
            $fileName = 'products_' . Str::random(10) . now() . '.csv';
        
            // Generate the file
            $temporaryFilePath = storage_path('app/temp/' . $fileName);
            Excel::store(new ExportProduct, $temporaryFilePath);
        
            // Move the file to the desired storage location
            $storagePath = 'csv/' . $fileName;
            Storage::move($temporaryFilePath, $storagePath);

            // Download the file
            $fileContents = Storage::get($storagePath);
            response()->download($fileContents, $fileName)->deleteFileAfterSend();
        } catch (\Throwable $th) {
            info($th->getMessage());
        }
    }
}