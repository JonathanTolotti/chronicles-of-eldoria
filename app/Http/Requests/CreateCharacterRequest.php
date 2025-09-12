<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateCharacterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:20',
                'regex:/^[a-zA-Z0-9\s]+$/',
                'unique:characters,name'
            ],
            'class' => [
                'required',
                'string',
                Rule::in(['warrior', 'mage', 'archer', 'rogue'])
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O nome do personagem é obrigatório.',
            'name.min' => 'O nome deve ter pelo menos 3 caracteres.',
            'name.max' => 'O nome deve ter no máximo 20 caracteres.',
            'name.regex' => 'O nome pode conter apenas letras, números e espaços.',
            'name.unique' => 'Este nome já está sendo usado por outro personagem.',
            'class.required' => 'A classe do personagem é obrigatória.',
            'class.in' => 'A classe selecionada é inválida.',
        ];
    }
}
