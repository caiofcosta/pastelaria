<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProdutoRequest;
use App\Http\Resources\ProdutoResource;
use App\Models\Produto;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
         //itens por página
         $perPage = $request->input('per_page', 10);
         // produtos
         $produtos = Produto::paginate($perPage);
         return response()->json(['message' => 'Produto encontrados', 'dados' =>$produtos], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProdutoRequest $request)
    {
        $dados = $request->except('foto');
        $image = $request->file('foto');
        $dados['foto'] = $image->store('fotos', 'public');
        $produto = Produto::create($dados);

        return response()->json(['message' => 'Produto salvo com sucesso', 'dados' => new ProdutoResource($produto)], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $produto = Produto::find($id);
        if (!$produto) {
            return response()->json(['message' => 'Produto não encontrado'], 404);
        }

        return response()->json(['message' => 'Produto encontrado', 'dados' => $produto], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProdutoRequest $request, string $id)
    {
         $produto = Produto::find($id);
         if (!$produto) {
             return response()->json(['message' => 'Produto não encontrado'], 404);
         }
         $produto->update($request->all());

         return response()->json(['message' => 'Produto atualizado com sucesso', 'dados' =>$produto], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         // Busca
         $produto = Produto::find($id);

         if (!$produto) {
             return response()->json(['message' => 'Produto não encontrado'], 404);
         }

         $produto->delete();

         return response()->json(['message' => 'Produto excluído com sucesso'], 200);
    }


}
