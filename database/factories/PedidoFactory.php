<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Pedido;
use App\Models\Produto;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pedido>
 */
class PedidoFactory extends Factory
{

    protected $model = Pedido::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cliente = User::factory()->create();
        $produto = Produto::factory()->create();

        return [
            'user_id' => $cliente->id,
            'produtos' => [
                [
                    'id' => $produto->id,
                    'preco' => $produto->preco,
                    'quantidade' => $this->faker->numberBetween(1, 5),
                ],
            ],

        ];
    }
}
