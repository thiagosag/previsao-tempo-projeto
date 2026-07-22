<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetWeatherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Permite que a requisição seja processada
    }

    public function rules(): array
    {
        return [
            'city' => ['nullable', 'string', 'min:2', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'city.min' => 'O nome da cidade deve ter pelo menos 2 caracteres.',
            'city.max' => 'O nome da cidade é muito longo.',
        ];
    }
}