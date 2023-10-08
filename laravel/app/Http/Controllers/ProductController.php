<?php

namespace App\Http\Controllers;

use App\Exports\ExportProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $products)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $products)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $products)
    {
        //
    }

    public function exportCSVFile() 
    {
        try {
            $file = Excel::download(new ExportProduct , 'Products.csv');
            return $file;
            
            $fileName = 'Products.csv';
        
            // Generate the file
            $temporaryFilePath = storage_path('app/temp/' . $fileName);
            Excel::store(new ExportProduct, $temporaryFilePath);
        
            // Move the file to the desired storage location
            $storagePath = 'csv/' . $fileName;
            Storage::move($temporaryFilePath, $storagePath);

            // Download the file
            $fileContents = Storage::get($storagePath);
            return response()->download($fileContents, $fileName)->deleteFileAfterSend();
            
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

    } 
}
