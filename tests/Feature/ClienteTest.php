<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ClienteTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateCliente()
    {
        $clienteData = [
            'name' => 'João Silva',
            'email' => 'joao@example.com',
            'telefone' => '(99) 99999-9999',
            'data_nascimento' => '1990-01-01',
            'endereco' => 'Rua Teste, 123',
            'complemento' => 'Apto 101',
            'bairro' => 'Centro',
            'cep' => '12345-678',
            'password' => '12345678'
        ];

        $response = $this->postJson('/api/cliente', $clienteData);

        $response->assertStatus(201);
        unset($clienteData['password']);
        $this->assertDatabaseHas('users', $clienteData);
    }

    public function testReadClientes()
    {
        $clientes = User::factory()->count(5)->create();

        $response = $this->getJson('/api/clientes');

        $response->assertStatus(200);

        $response->assertJsonStructure([
                    'message',
                    'dados' => [
                        'data' => [
                            '*' => ['id', 'name', 'email', 'telefone', 'data_nascimento', 'endereco', 'complemento', 'bairro', 'cep'],
                        ],
                        "first_page_url",
                        "from",
                        "last_page",
                        "last_page_url",
                        "links",
                        "next_page_url",
                        "path",
                        "per_page",
                        "prev_page_url",
                        "to",
                        "total",
                    ],
                ]);

            $response->assertJsonCount(5, 'dados.data');
    }

    public function testReadCliente()
    {
        $cliente = User::factory()->create();

        $response = $this->getJson('/api/cliente/' . $cliente->id);

        $response->assertStatus(200);
        $response->assertJson([
            "message"=> "Cliente encontrado",
            "dados"=> [
                'id' => $cliente->id,
                'name' => $cliente->name,
                'email' => $cliente->email,
                "telefone" =>  $cliente->telefone,
                "data_nascimento" =>  $cliente->data_nascimento,
                "endereco" =>  $cliente->endereco,
                "complemento" =>  $cliente->complemento,
                "bairro" =>  $cliente->bairro,
                "cep" =>  $cliente->cep,
            ]
        ]);
    }

    public function testUpdateCliente()
    {
        $cliente = User::factory()->create();

        $updatedClient =  [
            'name' => 'João Silva',
            'email' => 'joao@example.com',
            'telefone' => '(99) 99999-9999',
            'data_nascimento' => '1990-01-01',
            'endereco' => 'Rua Teste, 123',
            'complemento' => 'Apto 101',
            'bairro' => 'Centro',
            'cep' => '12345-678',
        ];

        $response = $this->putJson('/api/cliente/' . $cliente->id, $updatedClient);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', $updatedClient);
    }

    public function testDeleteCliente()
    {
        $cliente = User::factory()->create();

        $response = $this->deleteJson('/api/cliente/' . $cliente->id);

        $response->assertStatus(200);

        $response->assertJson(['message' => 'Cliente excluído com sucesso']);

        $this->assertSoftDeleted('users', ['id' => $cliente->id]);
    }
}
