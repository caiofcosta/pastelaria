<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Produto;
use Illuminate\Http\UploadedFile;

class ProdutoTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateProduto()
    {
        $produto = [
            'nome' => 'Produto de Teste',
            'preco' => 19.99,
            'foto' => UploadedFile::fake()->image('produto.jpg'),
        ];

        $response = $this->postJson('/api/produto', $produto);

        $response->assertStatus(201);
        unset($produto['foto']);
        $this->assertDatabaseHas('produtos', $produto);
    }

    public function testReadProdutos()
    {
        $produtos = Produto::factory()->count(5)->create();

        $response = $this->getJson('/api/produtos');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'message',
            'dados' => [
                'data' => [
                    '*' => ["id","nome","preco","foto","created_at","updated_at","deleted_at"],
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

    public function testShowProduto()
    {
        // Cria um produto usando a factory e o salva no banco de dados
        $produto = Produto::factory()->create();

        // Faz uma requisição para obter os detalhes do produto recém-criado
        $response = $this->getJson('/api/produto/' . $produto->id);

        // Verifica se a resposta tem o status HTTP 200 (OK)
        $response->assertStatus(200);

        // Verifica se a resposta contém a estrutura JSON correta
        $response->assertJsonStructure([
            'message',
            'dados' => [
                'id',
                'nome',
                'preco',
                'foto'
            ],
        ]);

        // Verifica se os detalhes do produto na resposta correspondem aos dados do produto criado
        $response->assertJson([
            'message' => 'Produto encontrado',
            'dados' => [
                'id' => $produto->id,
                'nome' => $produto->nome,
                'preco' => $produto->preco,
                'foto' => $produto->foto
            ],
        ]);
    }

    public function testUpdateProduto()
    {

        $produto = Produto::factory()->create();

        $dadosAtualizados = [
            'nome' => 'Novo Nome do Produto',
            'preco' => 9.99,
        ];

        $response = $this->putJson('/api/produto/' . $produto->id, $dadosAtualizados);

        $response->assertStatus(200);


        $response->assertJson( ['message' => 'Produto atualizado com sucesso',
                                        'dados' => [
                                            'id' => $produto->id,
                                            'nome' =>  $dadosAtualizados['nome'],
                                            'preco' =>  $dadosAtualizados['preco']
                                        ]
                            ]);


        $this->assertDatabaseHas('produtos', [
            'id' => $produto->id,
            'nome' => $dadosAtualizados['nome'],
            'preco' => $dadosAtualizados['preco'],
        ]);
    }

    public function testDeleteProduto()
    {
        $produto = Produto::factory()->create();

        $response = $this->deleteJson('/api/produto/' . $produto->id);

        $response->assertStatus(200);

        $response->assertJson(['message' => 'Produto excluído com sucesso']);

        $this->assertSoftDeleted('produtos',["id" =>$produto->id]);
    }
}
