<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierControllerr extends Controller
{
    public function index()
    {
        return response()->json(Supplier::all());
    }
    public function store(Request $request)
    {
        $supplier = $request->validate([
            'supplier_name' => 'required',
            'contact' => 'required',
            'supplier_email' => 'required',
            'supplier_status' => 'required'
        ]);
        $data = Supplier::create($supplier);
        if(!$data){
            return response()->json([
                'success' => false,
                'message' => 'Data gagal dibuat'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dibuat'
        ], 200);
        
    }
    public function show($id)
    {
        $data = Supplier::find($id);

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
        $supplier = Supplier::find($id);

    if (!$supplier) {
        return response()->json([
            'message' => 'Data tidak ditemukan'
        ], 404);
    }

    $supplier->update([
        'supplier_name' => $request->supplier_name,
        'contact' => $request->contact,
        'supplier_email' => $request->supplier_email,
        'supplier_status' => $request->supplier_status,
    ]);

    return response()->json([
        'message' => 'Berhasil update',
        'data' => $supplier
    ], 200);
    }
    public function destroy($id)
    {
        $data = Supplier::find($id);
        $data->delete();

        return response()->json([
            'success'=>true,
            'message' => 'Data berhasil dihapus'
        ]);
    }
}