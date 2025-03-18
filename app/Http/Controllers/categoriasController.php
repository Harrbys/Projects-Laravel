<?php

namespace App\Http\Controllers;

use App\Models\categorias;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class categoriasController extends Controller
{
    public function index()
    {
        return categorias::all();
    }

    public function store(Request $request)
    {
        $rules = [
            'codigo' => 'required|unique:categorias',
            'nombre' => 'required',
            'descripcion' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            Log::info('Error de request no encontrada');
            return response()->json($validator->errors(), 400);
        }else{
            $data = $request->all();
            $create = categorias::create($data);
            return response()->json("Categoria registrada exitosamente", 201);
        }

    }

    public function show($id)
    {
        return categorias::find($id);
    }

    public function update(Request $request){
        $rules = [
            'codigo' => 'required',
            'nombre' => 'required',
            'descripcion' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
        return response()->json($validator->errors(), 400);
    } else {
        // Buscar el producto usando el code
        $product = categorias::where('codigo', $request->codigo)->first();

        if (!$product) {
            return response()->json(['message' => 'categoria no encontrado'], 404);
        }else{
            $data = $request->all();
            $product->update($data);
            return response()->json("Categoria actualizado exitosamente", 200);
        }
    }
    }
}
