<?php

namespace App\Http\Controllers;

use App\Http\Requests\PedidoRequest;
use App\Http\Resources\PedidoResource;
use App\Mail\PedidoCriadoMail;
use App\Models\Pedido;
use App\Models\Produto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Mail;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $pedidosPaginados = Pedido::paginate($perPage);

        $pedidosPaginados->getCollection()->transform(function ($pedido) {
            return new PedidoResource($pedido);
        });


        return response()->json(['message' => 'Pedidos encontrados', 'dados' => new LengthAwarePaginator(
            $pedidosPaginados->getCollection(),
            $pedidosPaginados->total(),
            $pedidosPaginados->perPage(),
            $pedidosPaginados->currentPage(),
            ['path' => $request->url()]
        )], 200);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PedidoRequest $request)
    {

        /**************/
        $pedido = Pedido::create([
            'user_id' => $request->input('cliente_id'),
            'produtos' =>  $this->arrayProdutoQuantidade($request->input('produtos')),
        ]);

        // envia e-mail para o cliente
        Mail::to($pedido->cliente->email)->send(new PedidoCriadoMail($pedido));

        return response()->json(['message' => 'Pedido salvo com sucesso', 'dados' =>new PedidoResource($pedido)], 201);
    }
    /**
     * Processa um array de produtos e adiciona informações de preço.
     *
     * @param array $produtos O array de produtos com suas quantidades e outros dados.
     * @return array Um novo array de produtos com informações de preço adicionadas.
     */
    public function arrayProdutoQuantidade(array $produtos)
    {
        $produtosComPreco= [];
        foreach ($produtos as $produto)
        {

            $produtoDetalhes = Produto::select('id', 'preco')->find($produto['id']);
            unset($produto['id']);
            $produtosComPreco[] = [
                'id' => $produtoDetalhes->id,
                'nome' => $produtoDetalhes->nome,
                'preco' => $produtoDetalhes->preco,
                ...$produto,
            ];
        }
        return  $produtosComPreco;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pedido = Pedido::find($id);
        if (!$pedido) {
            return response()->json(['message' => 'Pedido não encontrado'], 404);
        }

        return response()->json(['message' => 'Pedido encontrado', 'dados' => new PedidoResource($pedido)], 200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pedido = Pedido::find($id);
        if (!$pedido) {
            return response()->json(['message' => 'Pedido não encontrado'], 404);
        }
        $pedido->update($request->all());

        return response()->json(['message' => 'Pedido atualizado com sucesso', 'dados'=> new PedidoResource($pedido)], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pedido = Pedido::find($id);
        if (!$pedido) {
            return response()->json(['message' => 'Pedido não encontrado'], 404);
        }
        $pedido->delete();

        return response()->json(['message' => 'Pedido excluído com sucesso'], 200);
    }
}
