<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return response()->json(['products' => $products], 200);
    }
    public function addProduct(Request $request){
        try {
            //code...
            $request->validate([
                'name' => 'required|string',
                'description' => 'required|string',
                'price' => 'required',
                'url' => 'string',
                'vendor' => 'string',
            ]);
    
            $product = new Product;
            // Mengisi data produk
            $product->name = $request->name;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->url = $request->url;
            $product->vendor = $request->vendor;
            $product->save();
            
    
            return response()->json([
                'message' => 'mengirim berhasil',
                'data' => $product,
            ], 200);
        } catch (Exception $error) {
            return response()->json([
                'message' => 'something error',
                'data' => $error,
            ], 500);
        }
        
    }
}
