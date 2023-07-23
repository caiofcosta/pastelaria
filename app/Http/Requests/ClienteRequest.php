<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClienteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        // id do cliente da rota.
        $clienteId = $this->route('cliente');

        // se é post novo registro
        $isCreating = $this->isMethod('post');

        // validação senha
        $passwordRules = [
            'nullable',
            'min:8',
        ];

        if ($isCreating) {
            // Se estiver criando um novo cliente, torna o campo de senha obrigatório.
            $passwordRules[] = 'required';
        }

        return [
            'name' => 'required',
            'email' => 'required|email|unique:users,email' . ($this->isMethod('put') ? ',' . $clienteId : ''),
            'telefone' => 'required',
            'data_nascimento' => 'required|date',
            'endereco' => 'required',
            'complemento' => 'nullable',
            'bairro' => 'required',
            'cep' => 'required',
            'password' => $passwordRules,
        ];
    }
}
