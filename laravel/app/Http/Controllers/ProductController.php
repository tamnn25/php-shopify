<?php

namespace App\Http\Controllers;

use App\Exports\ExportProduct;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{

    public const SUB_END_POINT = 'admin/api/';

    public const PRODUCT_END_POINT = 'products.json';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $auto=false)
    {
        $user = User::first();
        $date = $this->getCreatedAtAttribute($user->created_at);
        $url = Product::SUB_END_POINT . $date . '/' . Product::PRODUCT_END_POINT;

        $response = $user->api()->rest('GET', $url);
        $products = $response['body']->container['products'];

        foreach ($products as $product) { 
            $check_product = Product::where('shopify_product_id',$product['id']);

            if (!$check_product->exists()) {
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

        $products = Product::orderBy('id','desc')->paginate(10);

        return redirect('/ajax-table');
    }

    public function getAll(Request $request)
    {
        if (!is_null(Auth::user())) {
            $products = Product::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->get();

            return redirect('/ajax-table');
        }else {
            return redirect('/ajax-table');
        }
    }

    public function exportCSVFile(Request $request) 
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

    public function getCreatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('Y-m');
    }

    public function getProduct(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);

        $products = Product::orderBy('id', 'desc')->paginate($perPage);

        return response()->json([
            'products' => $products->items(),
            'total_records' => $products->total(),
        ]);
    }
}
