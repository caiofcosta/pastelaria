<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Pedido;
use App\Models\Produto;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PedidoTest extends TestCase
{
    use RefreshDatabase;

    public function testStorePedido()
    {
        $cliente = User::factory()->create();
        $produto = Produto::factory()->create();

        $dadosPedido = [
            'cliente_id' => $cliente->id,
            'produtos' => [
                [
                    'id' => $produto->id,
                    'quantidade' => 2,
                    'obs' => 'teste'
                ],
            ],
        ];

        $response = $this->postJson('/api/pedido', $dadosPedido);

        $response->assertStatus(201);

        $pedidoCriado = json_decode($response->getContent(), true);

        $response->assertJson( ['message' => 'Pedido salvo com sucesso',
                'dados' => [
                    'cliente_id' => $cliente->id,
                    'produtos' => [
                        [
                            'id' => $produto->id,
                            'quantidade' => 2,
                            'obs' => 'teste'
                        ],
                    ],
                ]
        ]);

        // Verifica se o pedido foi criado corretamente no banco de dados
        $this->assertDatabaseHas('pedidos', [
            'id' => $pedidoCriado['dados']['id'],
            'user_id' => $dadosPedido['cliente_id'],
        ]);

    }

    public function testIndexPedidos()
    {
         Pedido::factory()->count(15)->create();

        $response = $this->getJson('/api/pedidos');

        $response->assertStatus(200);

         $response->assertJsonStructure([
            'message',
            'dados' => [
                'data' => [
                    '*' => [
                        'id',
                        'cliente_id',
                        'produtos',
                    ],
                ],
                'first_page_url',
                'from',
                'last_page',
                'last_page_url',
                'links',
                'next_page_url',
                'path',
                'per_page',
                'prev_page_url',
                'to',
                'total',
            ],
        ]);

        $response->assertJsonCount(10, 'dados.data');
    }

    public function testShowPedido()
    {

        $pedido = Pedido::factory()->create();

        $response = $this->getJson('/api/pedido/' . $pedido->id);

        $response->assertStatus(200);

        $response->assertJson([
            'message' => 'Pedido encontrado',
            'dados' => [
                'id' => $pedido->id,
                'cliente_id' => $pedido->user_id,
                'produtos' => $pedido->produtos,
            ],
        ]);
    }

    public function testUpdatePedido()
    {
        $pedido = Pedido::factory()->create();

        // Dados atualizados do pedido
        $dadosAtualizados = [
            'cliente_id' =>  $pedido->user_id,
            'produtos' => [
                ['id' =>  $pedido->produtos[0]['id'], 'quantidade' => 30],
            ],
        ];

        $response = $this->putJson('/api/pedido/' . $pedido->id, $dadosAtualizados);

        $response->assertStatus(200);

        $response->assertJson( ['message' => 'Pedido atualizado com sucesso',
                'dados' => [
                    'cliente_id' => $dadosAtualizados['cliente_id'],
                    'produtos' => $dadosAtualizados['produtos'],
                ]
        ]);

        $this->assertDatabaseHas('pedidos', [
            'id' => $pedido->id,
            'user_id' => $dadosAtualizados['cliente_id'],
            'produtos' => json_encode($dadosAtualizados['produtos']),
        ]);
    }

    public function testDestroyPedido()
    {

        $pedido = Pedido::factory()->create();

        $response = $this->deleteJson('/api/pedido/' . $pedido->id);

        $response->assertStatus(200);

        $response->assertJson(['message' => 'Pedido excluído com sucesso']);

        $this->assertSoftDeleted('pedidos', ['id' => $pedido->id]);
    }

}
