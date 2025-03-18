<?php

namespace App\Http\Controllers;

use App\Models\productos_check;
use Illuminate\Http\Request;

class ProductosChecksController extends Controller
{
    public function index(){
        return productos_check::all();
    }


    public function store(Request $request){

        $request->validate([
            'codigo' => 'required',
            'productos' => 'required|array',
            'productos.*' => 'exists:productos,id',
            'cliente_id' => 'required'
        ]);


        if (!$request->productos) {
            return response()->json([
                'succes' => false,
                'message' => 'Datos incompletos'
            ],400);
        }

        // dd($request->productos);

        $existeProchecks = productos_check::where('codigo', $request->codigo)->first();

        // dd("hola", $request->productos);

        if (!$existeProchecks){
            $productoCheck = productos_check::create([
                'codigo' => $request->codigo,
                'productos' => $request->productos,
                'cliente_id' => $request->cliente_id
            ]);
            
            $productoCheck->productos()->attach($request->productos);

            return response()->json([
                'succes' => True,
                'message' => 'Produtos checks registrado con exito'
            ],200);
        }else{
            return response()->json([
                'succes' => false,
                'message' => 'Registro duplicado Error en el registro'
            ],400);
        }
        
  }
}