<?php

namespace App\Http\Controllers;

use App\Models\itemsIncoming;
use App\Models\itemsOutgoing;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ItemIncomingController extends Controller
{
    public function index()
    {
        $transaction = itemsIncoming::with('product', 'supplier', 'user')->get();

        return response()->json([
            'data' => $transaction
        ]);
    }
    public function store(Request $request)
    {
        $user = auth()->user();
        $data = $request->validate([
            'product_id' => 'required',
            'supplier_id'=>'required',
            'incoming_date' => 'required',
            'qty_incoming_items' => 'required|numeric|min:1'
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
        $supplier = Supplier::find($data['supplier_id']);
        if(!$supplier){
            return response()->json([
                'success' => false,
                'message' => 'Supplier tidak ditemukan'
            ], 404);
        }

        // Cek apakah stok cukup atau produk berstatus tidak tersedia
        $status = strtolower((string) $product->product_status);
        if ($status === 'unavailable' || $status === 'unavailable') {
            return response()->json([
                'success' => false,
                'message' => 'Stok tidak cukup atau produk tidak tersedia untuk dipinjam'
            ], 400);
        }
        $statusSupplier = strtolower((string) $supplier->supplier_status);
        if ($statusSupplier === 'inactive'){
            return response()->json([
                'success' => false,
                'message' => 'Supplier tidak aktif'
            ]);
        }

        // Buat record peminjaman
        $item = itemsIncoming::create($data);
        if(!$item){
            return response()->json([
                'success' => false,
                'message' => 'Data gagal dibuat'
            ], 500);
        }

        // Kurangi qty di tabel products
        $product->increment('qty', $data['qty_incoming_items']);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dibuat'
        ], 200);
    }
    public function show($id)
    {
        $data = itemsIncoming::find($id);
        
        if(!$data){
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        return response()->json([
            'data' => $data
        ]);
    }
    public function update(Request $request, $id)
{
    $transaction = itemsIncoming::find($id);

    if (!$transaction) {
        return response()->json([
            'success' => false,
            'message' => 'Data tidak ditemukan',
        ], 404);
    }

    // Ambil produk terkait
    $product = Product::find($transaction->product_id);

    // 1. Kembalikan qty lama ke stok
    if ($product) {
        $product->qty -= $transaction->qty_incoming_items;
        $product->save();
    }

    // Update transaksi incoming
    $transaction->product_id = $request->product_id;
    $transaction->supplier_id = $request->supplier_id;
    $transaction->qty_incoming_items = $request->qty_incoming_items;
    $transaction->incoming_date = $request->incoming_date;
    $transaction->save();

    // 2. Tambahkan qty baru ke produk
    $updatedProduct = Product::find($request->product_id);
    if ($updatedProduct) {
        $updatedProduct->qty += $request->qty_incoming_items;
        $updatedProduct->save();
    }

    return response()->json([
        'success' => true,
        'message' => 'Data berhasil diperbarui',
        'data' => $transaction
    ], 200);
}

    public function destroy($id)
    {
        $data = itemsIncoming::where('id', $id)->first();
        
         if(!$data){
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        $data->delete();

        return response()->json([
            'data' => $data,
            'success' => true,
            'message' => 'Data berhasil dihapus'
        ]);
    }
}