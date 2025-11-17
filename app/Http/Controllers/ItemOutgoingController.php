<?php

namespace App\Http\Controllers;

use App\Models\itemsOutgoing;
use App\Models\Product;
use Illuminate\Http\Request;

class ItemOutgoingController extends Controller
{
    public function index()
    {
        $data = itemsOutgoing::with(['product', 'user'])->get();
        return response()->json([
            'data'=>$data
        ]);
    }
    public function store(Request $request)
    {
        $user = auth()->user();
        $data = $request->validate([
            'product_id' => 'required',
            'outgoing_date' => 'required',
            'qty_outgoing_items' => 'required|numeric|min:1'
        ]);

        $data['user_id'] = $user->id;   
        // Ambil produk untuk pengecekan stok dan status
        $product = Product::find($data['product_id']);
        if(!$product){
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }

        // Cek apakah stok cukup atau produk berstatus tidak tersedia
        $status = strtolower((string) $product->product_status);
        if ($product->qty < $data['qty_outgoing_items'] || $status === 'unavailable' || $status === 'unabilable') {
            return response()->json([
                'success' => false,
                'message' => 'Stok tidak cukup atau produk tidak tersedia untuk dipinjam'
            ], 400);
        }

        // Buat record peminjaman
        $item = itemsOutgoing::create($data);
        if(!$item){
            return response()->json([
                'success' => false,
                'message' => 'Data gagal dibuat'
            ], 500);
        }

        // Kurangi qty di tabel products
        $product->decrement('qty', $data['qty_outgoing_items']);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dibuat'
        ], 200);
    }
}