<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClienteRequest;
use App\Models\User;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //itens por página
        $perPage = $request->input('per_page', 10);
        // dados
        $clientes = User::paginate($perPage);
        return response()->json(['message' => 'Clientes encontrados', 'dados' =>$clientes], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClienteRequest  $request)
    {
        $cliente = User::create($request->all());
        return response()->json(['message' => 'Cliente salvo com sucesso', 'dados' =>$cliente], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cliente = User::find($id);
        if (!$cliente) {
            return response()->json(['message' => 'Cliente não encontrado'], 404);
        }

        return response()->json(['message' => 'Cliente encontrado', 'dados' => $cliente], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClienteRequest  $request, string $id)
    {
         // verifica se existe cliente
         $cliente = User::find($id);
         if (!$cliente) {
             return response()->json(['message' => 'Cliente não encontrado'], 404);
         }
         $cliente->update($request->all());

         return response()->json($cliente);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

         $cliente = User::find($id);
         if (!$cliente) {
             return response()->json(['message' => 'Cliente não encontrado'], 404);
         }

         $cliente->delete();

         return response()->json(['message' => 'Cliente excluído com sucesso'], 200);
    }
}
