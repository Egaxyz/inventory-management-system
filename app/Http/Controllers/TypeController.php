<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TypeController extends Controller
{
    public function index()
    {
        return response()->json(Type::all());
    }
   public function store(Request $request)
{
    // Ambil id terakhir
    $lastType = Type::latest('id')->first();
    $nextId = $lastType ? $lastType->id + 1 : 1;

    // Buat kode otomatis
    $typeGNTCode = 'Type' . $nextId;

    // Buat data baru
    $type = new Type();
    $type->type_code = $typeGNTCode;
    $type->type_name = $request->type_name; 
    $type->description = $request->description; 
    $type->save();

    return response()->json([
        'message' => 'Tipe berhasil dibuat',
        'data' => $type
    ]);

}
    public function show($id)
    {
        $type = Type::find($id);

        if(!$type){
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
        return response()->json([
            'data' => $type
        ], 200);
    }
    public function update(Request $request, $id)
{
    $type = Type::find($id);

    if (!$type) {
        return response()->json([
            'message' => 'Data tidak ditemukan'
        ], 404);
    }

    $type->update([
        'type_name' => $request->type_name,
        'description' => $request->description,
    ]);

    return response()->json([
        'message' => 'Berhasil update',
        'data' => $type
    ], 200);
}

    public function destroy( $id)
    {
        $type = Type::where('id', $id)->first();

        if(!$type){
            return response()->json([
                'success' => false,
                'message' => 'Data Tidak Ditemukan',
            ], 404);
        }
        $type->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Dihapus'
        ]);
    }

}