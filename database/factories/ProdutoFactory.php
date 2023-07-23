<?php

namespace Database\Factories;

use App\Models\Produto;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produto>
 */
class ProdutoFactory extends Factory
{
    protected $model = Produto::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Cria uma imagem
        $temporaryImage = $this->faker->image(null, 400, 300);

        // Armazena a imagem
        $path = Storage::disk('public')->putFile('produtos', new File($temporaryImage));

        return [
            'nome' => $this->faker->word,
            'preco' => $this->faker->randomFloat(2, 10, 100),
            'foto' => $path
        ];
    }
}
