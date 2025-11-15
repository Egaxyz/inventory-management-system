<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return response()->json(Product::all());
    }
    public function store(Request $request)
    {
        $lastType = Product::latest('id')->first();
        $nextId = $lastType ? $lastType->id + 1 : 1;

        // Buat kode otomatis
        $productGNTCode = 'PN' . $nextId;

        // Buat data baru
        $product = new Product();
        $product->type_id = $request->type_id; 
        $product->product_name = $request->product_name; 
        $product->product_code = $productGNTCode;
        $product->product_status = $request->product_status; 
        $product->qty = $request->qty; 
        $product->price = $request->price; 
        $product->description = $request->description; 
        $product->save();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dibuat',
            'data' => $product
        ]);
    }
    public function show($id)
    {
        
        $data = Product::find($id);

          if (!$data) {
            return response()->json([
                'success' => false,
                'messsage' => 'Data tidak ditemukan'
            ], 404);
        }
        return response()->json([
            'data' => $data,
        ], 200);
    }
    public function update(Request $request, $id) 
    {
        $data = Product::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $data->type_id = $request->type_id ?? $data->type_id;
        $data->product_name = $request->product_name ?? $data->product_name;
        $data->product_status = $request->product_status ?? $data->product_status;
        $data->qty = $request->qty ?? $data->qty;
        $data->price = $request->price ?? $data->price;
        $data->description = $request->description ?? $data->description;
        $data->save();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diperbarui',
            'data' => $data
        ], 200);
    }
    public function destroy($id) 
    {
        $data = Product::where('id', $id)->first();

        if (!$data) {
            return response()->json([
                'success' => false,
                'messsage' => 'Data tidak ditemukan'
            ], 404);
        }
        $data->delete();
        return response()->json([
            'data' => $data,
        ], 200);
    }
}